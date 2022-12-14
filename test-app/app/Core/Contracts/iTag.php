<?php
declare(strict_types = 1);

namespace Core\Contracts;

interface iTag
{
	// Геттер имени:
	public function getName();
	
	// Геттер текста:
	public function getText();
	
	// Геттер всех атрибутов:
	public function getAttrs();
	
	// Геттер одного атрибута по имени:
	public function getAttr($name);

	// Сеттер установки типа тега: opening | closing | single
	// перевести на enum
	public function setType($type);

	// Гетер типа тега
	public function getType();

	public function setValid($valid);
	
	public function isValid();
	
	public function setError($message);
	
	public function getError();
	
	// Открывающий тег, текст и закрывающий тег:
	public function show();
	
	// Открывающий тег:
	public function open();
	
	// Закрывающий тег:
	public function close();
	
	// Установка текста:
	public function setText($text);
	
	// Установка атрибута:
	public function setAttr($name, $value = true);
	
	// Установка атрибутов:
	public function setAttrs($attrs);
	
	// Удаление атрибута:
	public function removeAttr($name);
	
	public function addClass($className);
	
	// Удаление класса:
	public function removeClass($className);
}