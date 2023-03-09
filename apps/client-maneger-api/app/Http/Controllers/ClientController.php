<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use Domain\Client\Exceptions\ClientAlreadyExistException;
use Domain\Client\Exceptions\InvalidCpfException;
use Domain\Client\Exceptions\InvalidDateException;
use Domain\Client\Exceptions\InvalidPhoneException;
use Domain\Client\Exceptions\NotFoundClientExceptoin;
use Domain\Client\UseCases\CreateClient\CreateClient;
use Domain\Client\UseCases\CreateClient\DTO as CreateClientDTO;
use Domain\Client\UseCases\DeleteClient\DeleteClient;
use Domain\Client\UseCases\DeleteClient\DTO as DeleteClientDTO;
use Domain\Client\UseCases\GetClients\DTO;
use Domain\Client\UseCases\GetClients\GetClients;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Client\Repositories\ClientRepository;

class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
       $cpf = $request->query('cpf');

       $name = $request->query('name');

       $DTO = new DTO($cpf, $name);

       $getClientsUseCase = new GetClients(new ClientRepository());

        try {
            $clients = $getClientsUseCase->execute($DTO);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return response()->json($clients->response());
    }


    public function store(StoreClientRequest $request): JsonResponse
    {
        $DTO = new CreateClientDTO(
            $request->name,
            $request->cpf,
            $request->birth_date,
            $request->phone
        );

        $createUseCase = new CreateClient(new ClientRepository());

        try {
            $client = $createUseCase->execute($DTO);
        } catch (ClientAlreadyExistException $e) {
            return response()->json(['error' => ['client' => 'already exist' ]], 422);
        } catch (InvalidCpfException $e) {
            return response()->json(['error' => ['cpf' => 'invalid' ]], 422);
        } catch (InvalidPhoneException $e) {
            return response()->json(['error' => ['phone' => 'invalid' ]], 422);
        } catch (InvalidDateException $e) {
            return response()->json(['error' => ['birth_date' => 'invalid' ]], 422);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return response()->json($client->response(), 200);
    }


    public function destroy(int $id): JsonResponse
    {
        $DTO = new DeleteClientDTO($id);

        $deleteUseCase = new DeleteClient(new ClientRepository());

        try {
            $deleteUseCase->execute($DTO);
        } catch (NotFoundClientExceptoin $e) {
            return response()->json([$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['Server Error'], 500);
        }

        return  response()->json([], 204);
    }

}
