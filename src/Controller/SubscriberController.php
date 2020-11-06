<?php

namespace Mailer\Controller;

use GuzzleHttp\Psr7\Response;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\NotFoundException;
use Mailer\Model\Subscriber;
use Mailer\Service\SubscriberService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SubscriberController
{
    use ExtractsQueryParameters;

    public function list(ServerRequestInterface $request): ResponseInterface
    {
        $from = $this->getQueryParameter($request, 'from', FILTER_VALIDATE_INT);
        $limit = $this->getQueryParameter($request, 'limit', FILTER_VALIDATE_INT) ?? $_ENV['DEFAULT_RESULT_LIMIT'];

        $subscribers = Subscriber::get($from, $limit);
        $count = Subscriber::count();

        $values = [
            'total' => $count,
            'from' => $from,
            'limit' => $limit,
            'data' => $subscribers,
        ];

        $response = new Response();
        $response->getBody()->write(json_encode($values));

        return $response;
    }

    public function find(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $id = $args['id'];

        $subscriberService = new SubscriberService();
        $subscriber = $subscriberService->find($id);

        $response = new Response();
        $response->getBody()->write(json_encode($subscriber));

        return $response;
    }

    public function create(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $json = $request->getParsedBody();

        $existingEmailSubscriber = Subscriber::findBy('email', $json['email']);

        if ($existingEmailSubscriber !== false) {
            throw new BadRequestException('A subscriber with this email already exists');
        }

        $subscriber = Subscriber::fromArray($json);
        $subscriber->create();

        $result = ['result' => 'success', 'data' => $subscriber];

        $response = new Response();
        $response->getBody()->write(json_encode($result));

        return $response;
    }

    public function update(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $id = $args['id'];

        $json = $request->getParsedBody();

        $subscriber = Subscriber::find($id);

        if (!empty($json['email']) and $json['email'] !== $subscriber->getEmail()) {
            $existingEmailSubscriber = Subscriber::findBy('email', $json['email']);

            if ($existingEmailSubscriber !== false) {
                throw new BadRequestException('A subscriber with this email already exists');
            }
        }

        $result = $subscriber->update($json);

        $values = ['result' => 'success', 'affected' => $result, 'data' => $subscriber];

        $response = new Response();
        $response->getBody()->write(json_encode($values));

        return $response;
    }

    public function delete(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $id = $args['id'];

        $subscriber = Subscriber::find($id);

        if (empty($subscriber)) {
            throw new NotFoundException('Subscriber does not exist');
        }

        $deleted = $subscriber->delete();

        $result = ['result' => 'success', 'affected' => $deleted];
        $response = new Response();
        $response->getBody()->write(json_encode($result));

        return $response;
    }
}
