<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Products;

$app->get("/admin/products",function(){

    User::verifyLogin();
    $products= Products::listAll();
    $page= new PageAdmin();
    $page->setTpl("products", [
        "products"=>$products
    ]);
});

$app->get("/admin/products/create",function(){

    User::verifyLogin();
    
    $page= new PageAdmin();
    $page->setTpl("products-create");
});

$app->post("/admin/products/create",function(){

    //verifica se o usuario está logado
    User::verifyLogin();
    
    $product= new Products;

    //passa os dados digitados via post
    $product-> setData($_POST);

    //chama o método save pra verificar se já precisa salvar ou editar
    $product->save();

    //redireciona de volta pra pagina em que os produtos cadastrados são exibidos
    header("Location: /admin/products");
    exit;
});

$app->get("/admin/products/:idproduct", function($idproduct){

	User::verifyLogin();

	$product = new Products();

	$product->get((int)$idproduct);

	$page = new PageAdmin();

	$page->setTpl("products-update", [
		'product'=>$product->getValues()
	]);

});

$app->post("/admin/products/:idproduct", function($idproduct){

	User::verifyLogin();

	$product = new Products();

	$product->get((int)$idproduct);

	$product->setData($_POST);

	$product->save();

    /*Nessa linha, nós acessamos o arquivo e verificamos se o seu nome é diferente de vazio.
     Isso só será possível se um arquivo for enviado.
    Com essa verificação, evitamos o erro de não encontrar a imagem */

	if($_FILES["file"]["name"] !== "") $product->setPhoto($_FILES["file"]);

	header('Location: /admin/products');
	exit;

});

$app->get("/admin/products/:idproduct/delete",function($idproduct){

    User::verifyLogin();

    $product= new Products();

    $product->get((int)$idproduct);

    $product->delete();

    header("Location: /admin/products");
    exit;
});



?>