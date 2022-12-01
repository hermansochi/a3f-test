<?php
declare(strict_types = 1);

namespace Core\Html;

use Core\Html\Tag;

class TagParser //implements iPage
{
	private string $tag;
	private ?Tag $vTag;
	
	public function __construct($tag)
	{
		$this->tag = $tag;
	}

	public function setTag($tag): void
	{
		$this->tag = $tag;
	}

	public function getTag(): string
	{
		return $this->tag;
	}
	
	public function parse()
	{
		//Валидатор тегов сильно упрощен.
		for ($i = 0; $i < mb_strlen($this->tag); $i++) {
			//Проверяем первый и последний символы на < >
			if ($i === 0 and ($this->tag[$i] !== '<' or $this->tag[-1] !== '>')) {
				throw new \Exception('Invalid tag.');
			}

			//Если второй символ в теге это / считаем тег закрывающим
			if ($i === 1 and $this->tag[$i] === '/') {
				$tagType = 'closing';
			}

		}


		//return $this->vTag;
	}
	
}