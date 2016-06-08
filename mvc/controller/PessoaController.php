<?php
    
    $root = realpath(__DIR__."/../../");

    header('Content-Type: application/json'); // o retorno do controller é sempre em json
    
    require_once("$root/src/$.php"); // funções globais
    require_once("$root/src/DB.php"); // banco de dados
 
    try {
    
        parse_str(file_get_contents("php://input"), $request); // pega os parametros enviados (seja qual for o método)
        $method = $_SERVER['REQUEST_METHOD']; // pega o método que enviou os parametros
        $function = $_REQUEST['function']; // pega a função requisitada
        $id = $_REQUEST['id']; // id da pessoa
        
        parseRequest($request); // converter arrays JSON para arrays PHP
     
        switch ($function) {
            
            case 'ReceberCompras':{
                $pessoa = Pessoa::find($id);
                $pedidos = $pessoa->getPedidos();
                foreach($pedidos as $pedido){
                    if ($pedido->getTpPedido() === 'C'){
                        if ($pedido->getStEntrega() === 'PN'){
                            $itens = $pedido->getItens();
                            foreach($itens as $item){
                                if ($item->getStEntregaItem() === 'PN'){
                                    $entrega = array(
                                        "nrEntrega" => count($item->getEntregas()) + 1,
                                        "dtEntrega" => date('Y-m-d', time()),
                                        "qtEntrega" => $item->getQtRestante(),
                                        "idItem" => $item->getIdItem()
                                    );
                                    Entrega::persistEntrega($entrega);
                                }
                            }
                        }
                    }
                }
                echo json_encode(true);
                break;
            }
            
            case 'EntregarVendas':{
                $pessoa = Pessoa::find($id);
                $pedidos = $pessoa->getPedidos();
                foreach($pedidos as $pedido){
                    if ($pedido->getTpPedido() === 'V'){
                        if ($pedido->getStEntrega() === 'PN'){
                            $itens = $pedido->getItens();
                            foreach($itens as $item){
                                if ($item->getStEntregaItem() === 'PN'){
                                    $entrega = array(
                                        "nrEntrega" => count($item->getEntregas()) + 1,
                                        "dtEntrega" => date('Y-m-d', time()),
                                        "qtEntrega" => $item->getQtRestante(),
                                        "idItem" => $item->getIdItem()
                                    );
                                    Entrega::persistEntrega($entrega);
                                }
                            }
                        }
                    }
                }
                echo json_encode(true);
                break;
            }
            
            case 'QuitarCompras':{
                $pessoa = Pessoa::find($id);
                $pedidos = $pessoa->getPedidos();
                foreach($pedidos as $pedido){
                    if ($pedido->getTpPedido() === 'C'){
                        if ($pedido->getStPagamento() === 'PN'){
                            $pagamento = array(
                                "nrPagamento" => count($pedido->getPagamentos()) + 1,
                                "dtPagamento" => date('Y-m-d', time()),
                                "vlPagamento" => $pedido->getVlRestante(),
                                "tpPagamento" => $_REQUEST['tp'],
                                "idPedido" => $pedido->getIdPedido()
                            );
                            Pagamento::persistPagamento($pagamento);
                        }
                    }
                }
                echo json_encode(true);
                break;
            }
            
            case 'QuitarVendas':{
                $pessoa = Pessoa::find($id);
                $pedidos = $pessoa->getPedidos();
                foreach($pedidos as $pedido){
                    if ($pedido->getTpPedido() === 'V'){
                        if ($pedido->getStPagamento() === 'PN'){
                            $pagamento = array(
                                "nrPagamento" => count($pedido->getPagamentos()) + 1,
                                "dtPagamento" => date('Y-m-d', time()),
                                "vlPagamento" => $pedido->getVlRestante(),
                                "tpPagamento" => $_REQUEST['tp'],
                                "idPedido" => $pedido->getIdPedido()
                            );
                            Pagamento::persistPagamento($pagamento);
                        }
                    }
                }
                echo json_encode(true);
                break;
            }
     
            default: break; // default
        }
     
    } catch (Exception $e) {
        echo json_encode(array(
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        )); 
    }