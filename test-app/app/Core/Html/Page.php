<?php
declare(strict_types = 1);

namespace Core\Html;

use Core\Contracts\iPage;
use Core\Html\TagParser;
use Core\Html\Tag;

class Page implements iPage
{
	/**
	 * @var string $url URL страницы для парсинга
	 * @var ?string $fileContent контент загруженной страницы
	 * @var ?array $allTags массив в ключе 0 - теги страницы в текстовом виде, в ключе 1 - "виртуальные" теги класса Tag
	 * @var ?string $stats массив статистика распарсенной страницы. В ключе - тег, в значение кол-во вхождений на странице
	 */
	private string $url;
	private ?string $fileContent = null;
	private ?array $allTags = null;
	private ?array $stats = null;
	
	/**
	 * @param string $url URL для загрузки страницы
	 * @return void
	 */
	public function __construct(string $url)
	{
		$this->url = $url;
	}

	/**
	 * Установить URL для загрузки страницы.
	 * Так же URL можно задать при создании объекта.
	 * @param string $url
	 * @return void
	 */
	public function setUrl(string $url): void
	{
		$this->url = $url;
	}

	/**
	 * Получить URL.
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->name;
	}
	
	/**
	 * Загрузить страницу, сохранить ее в приватном свойстве и вернуть.
	 * Для упрощения без обработки ошибок
	 * @return string
	 */
	public function getContent(): string
	{
		$content = file_get_contents($this->url);
		if (!(false === $content)) {
			$this->fileContent = $content;
		}
		return $this->fileContent;
	}

	/**
	 * Распарсить ранее загруженную страницу.
	 * Вернет массив в 0 ключе которого массив с загруженными тегами,
	 * во 1 ключе массив с созданными "виртуальными" тегами Tag.
	 * Решение не красивое, но рефакторить в тестовом не стал.
	 * @throws \Exception Ошибки парсинга
	 * @return array
	 */
	public function parse(): array
	{
		preg_match_all('/<.*?>/', $this->fileContent, $this->allTags);

		if (count($this->allTags[0]) === 0) {
			throw new \Exception('Nothing to parse');
		}

		$parser = new TagParser();
		for ($i =0; $i < count($this->allTags[0]); $i++) {
			$parser->setTag($this->allTags[0][$i]);
			try {
				$vTags[] = $parser->parse();
			} catch (\Exception $e) {
				$vTags[] = (new Tag())->setError($e->getMessage());
			}
		}
		$this->allTags[] = $vTags;
		return $this->allTags;
	}

	/**
	 * Показать исходную и распарсенную страницы.
	 * @return void
	 */
	public function showVDom(): void
	{
		if (count($this->allTags[1]) === 0) {
			throw new \Exception('No vDom.');
		}
		printf("\nvDOM:\n%-80.80s| %-80.80s\n", 'Source:', 'Parsed:');
		for ($i = 0; $i < count($this->allTags[0]); $i++) {
			$tag = $this->allTags[1][$i];
			$tagStr = '';
			$tagDescription = '';
			if (!is_null($tag)) {
				$tagDescription = $tag->isValid() ? 'Tag valid ' : 'Tag invalid ';
				$tagDescription .= $tag->getType() . ' ';
				if (!$tag->isValid()) {
					$tagStr = $tag->getError();
				}
				if ($tag->getType() === 'closing') {
					$tagStr = ($tag->close());
				} else {
					$tagStr = ($tag->open());
				}
			}
			printf("%-80.80s| %-24.24s%-80.80s\n", $this->allTags[0][$i], $tagDescription, $tagStr);
		}
	}

	/**
	 * Посчитать статистику по тегам по распарсенной ранее странице
	 * Можно сделать приватным.
	 * Сохранит в свойствах и вернет массив в ключе - имя тега, в
	 * значении количество использований на странице.
	 * @throws \Exception Ошибки парсинга
	 * @return array
   */
	public function getStats(): array
	{
		if (count($this->allTags[1]) === 0) {
			throw new \Exception('No values for calculating statistics.');
		}

		foreach ($this->allTags[1] as $tag) {
			if (!is_null($tag)) {
				if (!$tag->isValid()) {
					$tagStr = $tag->getError();
				}
				if ($tag->getType() === 'closing') {
					$tagStr = ($tag->close());
				} else {
					$tagStr = ($tag->open());
				}
				$this->stats[] = $tagStr;
			}
		}
		$this->stats = array_count_values($this->stats);

		arsort($this->stats);
		return $this->stats;
	}

	/**
	 * Показать статистику по тегам на странице.
	 * @return void
	 */
	public function showStats(): void
	{
		if (count($this->stats) === 0) {
			throw new \Exception('No statistics.');
		}
		printf("\nTag statistics:\n");
		printf("%-20.20s| %-20.20s\n", 'Tag:', 'Count:');
		foreach ($this->stats as $tag => $count) {
			printf("%-20.20s| %-20.20s\n", $tag, $count);
		}
	}
}