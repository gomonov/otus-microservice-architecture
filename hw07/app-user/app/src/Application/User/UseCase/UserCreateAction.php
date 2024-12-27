<?php

namespace App\Application\User\UseCase;

use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\ProducerInterface;
use App\Application\Security\PasswordHashServiceInterface;
use App\Application\User\Exception\UserException;
use App\Application\User\Factory\UserFactoryInterface;
use App\Application\User\Model\UserModelInterface;
use App\Application\User\Repository\UserRepositoryInterface;
use App\Application\User\UseCase\Contract\UserCreateDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use JsonException;

readonly class UserCreateAction
{
    public function __construct(
        private UserFactoryInterface          $userFactory,
        private UserRepositoryInterface       $userRepository,
        private EntityStorageServiceInterface $entityStorageService,
        private PasswordHashServiceInterface  $passwordHashService,
        private ProducerInterface             $producer,
        private MessageFabricInterface        $messageFabric,
    ) {
    }

    /**
     * @param UserCreateDataInterface $data
     * @return UserModelInterface
     * @throws EntityStorageException
     * @throws UserException
     * @throws ProducerException
     * @throws JsonException
     */
    public function do(UserCreateDataInterface $data): UserModelInterface
    {
        $user = $this->userRepository->getByUsername($data->getUsername());
        if (null !== $user) {
            throw new UserException('Пользователь с таким логином уже существует');
        }

        $user = $this->userRepository->getByEmail($data->getEmail());
        if (null !== $user) {
            throw new UserException('Пользователь с таким email уже существует');
        }

        $user = $this->userFactory->create();
        $user->setUsername($data->getUsername());
        $user->setFirstName($data->getFirstName());
        $user->setLastName($data->getLastName());
        $user->setEmail($data->getEmail());
        $user->setPhone($data->getPhone());
        $user->setPassword($this->passwordHashService->hashPassword($user, $data->getPassword()));

        $this->entityStorageService->persist($user);
        $this->entityStorageService->flush();

        $message = $this->messageFabric->create();
        $message->setBody(json_encode(['action' => 'create', 'id' => $user->getId()], JSON_THROW_ON_ERROR));
        $this->producer->send($message, 'user.event');

        return $user;
    }
}