<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

//rota do link para acessar as categorias

$app->get("/admin/categories", function(){

	User::verifyLogin();

	$categories=Category::listAll();
	
	$page= new PageAdmin();

	$page->setTpl("categories",[
		'categories'=>$categories
	]);
});

//rotas pra criar a categoria

$app->get("/admin/categories/create", function(){

	User::verifyLogin();
	
	$page= new PageAdmin();

	$page-> setTpl("categories-create");
});


$app->post("/admin/categories/create", function(){
	
	User::verifyLogin();
	$category= new Category();
	
	$category->setData($_POST);
	
	$category->save();
	
	header("Location: /admin/categories");
	exit;

	$page= new PageAdmin();
	$page= setTpl("categories-create");
});

//rota pra deletar 
$app->get("/admin/categories/:idcategory/delete",function($idcategory){

	User::verifyLogin();
	$category= new Category();
	$category->get((int)$idcategory);
	$category->delete();

	header("Location: /admin/categories");
	exit;
});

//rotas pra atualizar a categoria

$app->get("/admin/categories/:idcategory",function($idcategory){
	User::verifyLogin();
	$category= new Category();
	$category->get((int)$idcategory);

	$page= new PageAdmin();
	$page-> setTpl("categories-update",[
		'category'=>$category->getValues()
	]);
});

$app->post("/admin/categories/:idcategory",function($idcategory){

	User::verifyLogin();
	$category= new Category();
	$category->get((int)$idcategory);

	$category->setData($_POST);
	$category->save();

	header("Location: /admin/categories");
	exit;
});

$app->get("/categories/:idcategory",function($idcategory){
	$category= new Category();
	$category->get((int)$idcategory);
	$page= new Page();
	$page->setTpl("category", [
		'category'=>$category->getValues(),
		'products'=>[]
	]);
});


?>