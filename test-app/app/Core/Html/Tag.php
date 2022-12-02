<?php
declare(strict_types = 1);

namespace Core\Html;

use Core\Contracts\iTag;

/**
 * Краткое описание в интерфейсе Core\Contracts\iTag;
 */
class Tag implements iTag
{
	private ?string $name;
	private array $attrs = [];
	private string $text = '';
	private string $type = '';
	private bool $isValid;
	private ?string $errorMessage = null;
	
	public function __construct($name = null)
	{
		$this->name = $name;
		$this->isValid = (is_null($name)) ? false : true;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getText()
	{
		return $this->text;
	}
	
	public function getAttrs()
	{
		return $this->attrs;
	}
	
	public function getAttr($name)
	{
		if (isset($this->attrs[$name])) {
			return $this->attrs[$name];
		} else {
			return null;
		}
	}
	
	public function show()
	{
		return $this->open() . $this->text . $this->close();
	}
	
	public function open()
	{
		$name = $this->name;
		$attrsStr = $this->getAttrsStr($this->attrs);
		
		return "<$name$attrsStr>";
	}
	
	public function close()
	{
		$name = $this->name;
		return "</$name>";
	}
	
	public function setText($text)
	{
		$this->text = $text;
		return $this;
	}
	
	public function setAttr($name, $value = true)
	{
		$this->attrs[$name] = $value;
		return $this;
	}
	
	public function setAttrs($attrs)
	{
		foreach ($attrs as $name => $value) {
			$this->setAttr($name, $value);
		}
		
		return $this;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setValid($valid)
	{
		$this->isValid = $valid;
	}

	public function isValid()
	{
		return $this->isValid;
	}

	public function setError($message)
	{
		$this->errorMessage = $message;
	}

	public function getError()
	{
		return $this->errorMessage;
	}
	
	public function removeAttr($name)
	{
		unset($this->attrs[$name]);
		return $this;
	}
	
	public function addClass($className)
	{
		if (isset($this->attrs['class'])) {
			$classNames = explode(' ', $this->attrs['class']);
			
			if (!in_array($className, $classNames)) {
				$classNames[] = $className;
				$this->attrs['class'] = implode(' ', $classNames);
			}
		} else {
			$this->attrs['class'] = $className;
		}
		
		return $this;
	}
	
	public function removeClass($className)
	{
		if (isset($this->attrs['class'])) {
			$classNames = explode(' ', $this->attrs['class']);
			
			if (in_array($className, $classNames)) {
				$classNames = $this->removeElem($className, $classNames);
				$this->attrs['class'] = implode(' ', $classNames);
			}
		}
		
		return $this;
	}
	
	private function getAttrsStr($attrs)
	{
		if (!empty($attrs)) {
			$result = '';
			
			foreach ($attrs as $name => $value) {
				if ($value === true) {
					$result .= " $name";
				} else {
					$result .= " $name=\"$value\"";
				}
			}
			
			return $result;
		} else {
			return '';
		}
	}
	
	private function removeElem($elem, $arr)
	{
		$key = array_search($elem, $arr);
		array_splice($arr, $key, 1);
		
		return $arr;
	}
}