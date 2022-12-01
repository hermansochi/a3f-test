<?php
declare(strict_types = 1);

namespace Core\Html;

use Core\Html\TagParser;

class Page //implements iPage
{
	private string $url;
	private string $fileContent = '';
	private ?array $allTags = null;
	private array $allvTags = [];
	
	public function __construct($url)
	{
		$this->url = $url;
	}

	public function setName($url): void
	{
		$this->url = $url;
	}

	public function getName(): string
	{
		return $this->name;
	}
	
	public function getContent(): string
	{
		$content = file_get_contents($this->url);

		if (!(false === $content)) {
			$this->fileContent = $content;
		}
		return $this->fileContent;
	}

	public function parseStageOne(): array
	{
		preg_match_all('/<.*?>/', $this->fileContent, $this->allTags);
		return $this->allTags[0];
	}

	public function parseStageTwo(): array
	{
		foreach ($this->allTags[0] as $elem)
		{
			var_dump($elem);
			$parser = new TagParser($elem);
			$allvTags[] = $parser->parse();
		}
		return $this->allvTags;
	}
	
}