<?php

namespace Mailer\Controller;

use GuzzleHttp\Psr7\Response;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\NotFoundException;
use Mailer\EmailChecker;
use Mailer\Model\Field;
use Mailer\Service\SubscriberService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FieldsController
{
    use ExtractsQueryParameters;

    public function list(ServerRequestInterface $request): ResponseInterface
    {
        $from = $this->getQueryParameter($request, 'from', FILTER_VALIDATE_INT);
        $limit = $this->getQueryParameter($request, 'limit', FILTER_VALIDATE_INT) ?? $_ENV['DEFAULT_RESULT_LIMIT'];

        $fields = Field::get($from, $limit);
        $count = Field::count();

        $values = [
            'total' => $count,
            'from' => $from,
            'limit' => $limit,
            'data' => $fields,
        ];

        $response = new Response();
        $response->getBody()->write(json_encode($values));

        return $response;
    }

    public function find(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $id = $args['id'];

        $field = Field::find($id);

        $response = new Response();
        $response->getBody()->write(json_encode($field));

        return $response;
    }

    public function create(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $json = $request->getParsedBody();

        // Note: Remove the unique title constraint once the fields are attached
        // to companies or something similar
        $existingTitleField = Field::findBy('title', $json['title']);

        if ($existingTitleField !== false) {
            throw new BadRequestException('A field with this title already exists');
        }

        $field = Field::fromArray($json);
        $field->create();

        $subscriberService = new SubscriberService();
        $subscriberService->attachNewField($field);

        $result = ['result' => 'success', 'data' => $field];

        $response = new Response();
        $response->getBody()->write(json_encode($result));

        return $response;
    }

    public function update(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $id = $args['id'];

        $json = $request->getParsedBody();

        $field = Field::find($id);

        if (empty($field)) {
            throw new NotFoundException('Field does not exist');
        }

        if (!empty($json['title']) and $json['title'] !== $field->getTitle()) {
            $existingTitleField = Field::findBy('title', $json['title']);

            if ($existingTitleField !== false) {
                throw new BadRequestException('A field with this title already exists');
            }
        }

        $result = $field->update($json);

        $values = ['result' => 'success', 'affected' => $result, 'data' => $field];

        $response = new Response();
        $response->getBody()->write(json_encode($values));

        return $response;
    }

    public function delete(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $id = $args['id'];

        $field = Field::find($id);

        if (empty($field)) {
            throw new NotFoundException('Field does not exist');
        }

        $deleted = $field->delete();

        $result = ['result' => 'success', 'affected' => $deleted];
        $response = new Response();
        $response->getBody()->write(json_encode($result));

        return $response;
    }
}
