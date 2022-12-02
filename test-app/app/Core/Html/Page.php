<?php
declare(strict_types = 1);

namespace Core\Html;

use Core\Contracts\iPage;
use Core\Html\TagParser;
use Core\Html\Tag;

class Page implements iPage
{
	private string $url;
	private string $fileContent = '';
	private ?array $allTags = null;
	private ?array $stats = null;
	
	public function __construct($url)
	{
		$this->url = $url;
	}

	public function setUrl($url): void
	{
		$this->url = $url;
	}

	public function getUrl(): string
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

	public function parse(): array
	{
		$this->parseStageOne();
		return $this->parseStageTwo();
	}

	public function parseStageOne(): array
	{
		preg_match_all('/<.*?>/', $this->fileContent, $this->allTags);
		return $this->allTags[0];
	}

	public function parseStageTwo(): array
	{
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

	public function showVDom(): void
	{
		if (count($this->allTags[1]) === 0) {
			throw new \Exception('No vDom.');
		}
		printf("%-80.80s| %-80.80s\n", 'Source:', 'Parsed:');
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