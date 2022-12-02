<?php
declare(strict_types = 1);

namespace Core\Contracts;

interface iPage
{
	public function setUrl($url): void;

	public function getUrl(): string;
	
	public function getContent(): string;
	
	public function parse(): array;

	public function parseStageOne(): array;
	
	public function parseStageTwo(): array;

	public function showVDom(): void;

	public function getStats(): array;

	public function showStats(): void;
}