<?php 
//-----------------------------------------------------------------------------------------------//
class MfgController
{
    private $_returnObject;

    public function __construct(private MfgGateway $gateway)
    {
        $this->_returnObject = new Response;
    }
//-----------------------------------------------------------------------------------------------//
    public function processRequest(string $method, ?string $id): void
    {
        //$_returnObject = new Response;

        if ($id === null)
        {
            if ($method == "GET") {
                $data = $this->gateway->getAll();
                $this->respondSuccess($data);
            } elseif ($method == "POST") {
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);

                if (! empty($errors))
                {
                    $this->respondUnprocessableEntity($errors);  
                    return;  
                }
                $id = $this->gateway->create($data);
                $this->respondCreated($id);

            } else {
                $this->responseMethodNotAllowed("GET, POST");
            }
        }
        else
        {
            if (is_numeric($id)) {
                $item = $this->gateway->getByID($id);
            }
            else {
                $item = $this->gateway->getByMFG($id);
            }
            
            if ($item === false) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                case "GET":
                    $this->respondSuccess($item);
                    break;
                case "PATCH":

                    $data = (array) json_decode(file_get_contents("php://input"), true);
                    $errors = $this->getValidationErrors($data);
                    if (! empty($errors))
                    {
                        $this->respondUnprocessableEntity($errors);  
                        return;  
                    }

                    $rows = $this->gateway->update($id, $data);

                    echo json_encode(["message" => "Manufacturer updated", "rows" => $rows]);
                    break;
                case "DELETE":
                    $rows = $this->gateway->delete($id);
                    echo json_encode(["message" => "Manufacturer deleted", "rows" => $rows]);

                    break;
                default:
                    $this->responseMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }
//-----------------------------------------------------------------------------------------------//
private function respondSuccess(array $items): void
{
    //$_returnObject = new Response;
    $this->_returnObject->setHttpStatusCode(200);
    $this->_returnObject->setSuccess(true);
    $this->_returnObject->setData($items);
    $this->_returnObject->send();

}
//-----------------------------------------------------------------------------------------------//
    private function respondUnprocessableEntity(array $errors): void
    {
        //$_returnObject = new Response;
        $this->_returnObject->setHttpStatusCode(422);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->addMessage($errors);
        $this->_returnObject->send();

    }
//-----------------------------------------------------------------------------------------------//
    private function responseMethodNotAllowed(string $allowed_methods): void
    {
        //$_returnObject = new Response;
        $this->_returnObject->setHttpStatusCode(405);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->allowedMethods($allowed_methods);
        $this->_returnObject->addMessage("Method not allowed");
        $this->_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
    private function respondNotFound(string $id): void
    {
        //$_returnObject = new Response;
        $this->_returnObject->setHttpStatusCode(404);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->addMessage("Manufacturer with $id not found");
        $this->_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
    private function respondCreated(string $id): void
    {
        //$_returnObject = new Response;
        $this->_returnObject->setHttpStatusCode(404);
        $this->_returnObject->setSuccess(true);
        $this->_returnObject->addMessage("Manufacturer Created, id = $id");
        $this->_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        if ($is_new && empty($data["mfg_name"]))
        {
            $errors[] = "Name is required";
        }
        if ($is_new && empty($data["mfg"]))
        {
            $errors[] = "Manufacturer code is required";
        }
        return $errors;
    }
}
//-----------------------------------------------------------------------------------------------//
