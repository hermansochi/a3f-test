<?php
declare(strict_types = 1);

namespace Core\Contracts;

interface iPage
{
	/**
	 * Установить URL для загрузки страницы.
	 * Так же URL можно задать при создании объекта.
	 * @param string $url
	 * @return void
	 */
	public function setUrl(string $url): void;

	/**
	 * Получить URL.
	 * @return string
	 */
	public function getUrl(): string;
	
	/**
	 * Загрузить страницу.
	 * @return string
	 */
	public function getContent(): string;
	
	/**
	 * Распарсить ранее загруженную страницу.
	 * @return array
	 */
	public function parse(): array;

	/**
	 * Показать исходную и распарсенную страницы
	 * @return void
	 */
	public function showVDom(): void;

	/**
	 * Посчитать статистику по тегам по распарсенной ранее странице
	 * @return array
	 */
	public function getStats(): array;

	/**
	 * Посчитать статистику по тегам
	 * @return void
	 */
	public function showStats(): void;
}