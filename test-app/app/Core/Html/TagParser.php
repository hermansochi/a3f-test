<?php
declare(strict_types = 1);

namespace Core\Html;

use Core\Html\Tag;

/**
 * Интерфейс для тестового счел излишним ))
 */
class TagParser //implements iPage
{
	/**
	 * @var ?string $tag Тег который будем парсить.
	 * @var ?Tag $vTag Виртуальный тег который будет создан по итогу парсинга.
	 * @var array $delimiterSymbols Валидные разделители частей тега.
	 * @var array $singletons список одиночных тегов.
	 */
	private ?string $tag;
	private ?Tag $vTag;
	private $delimiterSymbols = ['\n', '>',' ', '\t', '\e', '\f', '\v', '\r'];
	private $singletons = [
		'!doctype', 
		'area',
		'base',
		'br',
		'col',
		'embed',
		'hr',
		'img',
		'input',
		'keygen',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr'
	];
	
	public function __construct(string $tag = null)
	{
		$this->tag = $tag;
	}

	public function setTag(string $tag): void
	{
		$this->tag = $tag;
	}

	public function getTag(): string
	{
		return $this->tag;
	}
	
	/**
	 * Сначала замахнулся на полный парсинг, создание virtualDOM, но понял, что буду делать несколько дней.
	 * Поэтому только то просили. Теги и их кол-во на странице.
	 * @return Core\Html\Tag;
	 */
	public function parse(): Tag
	{
		/**
		 * Валидация тегов сильно упрощена.
		 * 
		 * Стадия парсинга tag | attrib | attribValue  - не актуально, аттрибуты решил не парсить ))
		 * @var stirng $parseStage
		 * Тип тега opening | closing | singleton
		 * @var string $tagType
		 */

		$parseStage = '';
		$tagType = '';
		$currentTag = $this->tag;

		if (is_null($currentTag)) {
			throw new \Exception('Nothing to parse');
		}

		//Отлавливаем комменты
		if (mb_substr($currentTag, 0, 4) === '<!--') {
			$this->vTag = new Tag('!-- comment --');
			$this->vTag->setType('comment');
			return $this->vTag;
		}

		for ($i = 0; $i < mb_strlen($currentTag); $i++) {
			//Проверяем первый и последний символы на < >
			if ($i === 0 and ($currentTag[$i] !== '<' or $currentTag[-1] !== '>')) {
				throw new \Exception('Invalid tag ' . $currentTag);
			}

			/**
			 * Если второй символ в теге это / и следующий символ входит в английский алфавит
			 * считаем тег закрывающим. Иначе проверяем что второй символ входит в английский алфавит
			 * или равен ! и считаем что парсинг в стадии tag, в ином случае считаем тег валидным.
			 * Здесь же отлавливаем комменты
			 */
			if ($i === 1) {
				if ($this->tag[1] === '/') {
					if (ctype_alpha($currentTag[2])) {
						$tagType = 'closing';
					} else {
						throw new \Exception('Invalid tag ' . $currentTag . ' Line: ' . __LINE__);
					}
				} else if (!ctype_alpha($currentTag[1]) and $currentTag[1] !== '!') {
					throw new \Exception('Invalid tag ' . $currentTag . ' Line: ' . __LINE__);
				}
			}
			$parseStage = 'tag';
			$divNameStartPos = $i + 1;
		  // Если тег закрывающий, то проверяем что все символы до конца тега входят в английский
			// алфавит, создаем виртуальный тег и возвращаем его.
			if ($tagType === 'closing' and $parseStage === 'tag') {
				$name = mb_substr($currentTag, 2, -1);
				if (!ctype_alpha($name)) {
					throw new \Exception('Invalid tag ' . $currentTag . ' Line: ' . __LINE__);
				} else {
					$this->vTag = new Tag($name);
					$this->vTag->setType($tagType);
					return $this->vTag;
				}
			}

			// Ищем конец названия тега. В рамках тестового не стал делать самую тяжелую часть -
			// Парсинг аттрибутов тега, хотя базовый класс Tag готов для этого, но это будет 
			// история на несколько дней ))
			for ($k = $i+1; $k < mb_strlen($currentTag); $k++) {
				if (in_array($currentTag[$k], $this->delimiterSymbols)) {
					$name = mb_substr($currentTag, $divNameStartPos, $k-1);
					if ($name[-1] === '/') {
						$name = mb_substr($name, 0, -1);
					}
					$this->vTag = new Tag($name);
					$this->vTag->setType($this->isSingleton($name) ? 'singleton' : 'opening');
					return $this->vTag;
				}
			}

		}
		//return $this->vTag;
	}

	/**
	 * Проверяем тег на то что он не парный по имени.
	 * Почему не использовал константы? Не знаю ))
	 * @param string $name Имя тега
	 * @return bool
	 */
	private function isSingleton (string $name): bool
	{
		return in_array($name, $this->singletons);
	}
}