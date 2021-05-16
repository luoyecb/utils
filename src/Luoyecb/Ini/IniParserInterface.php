<?php
namespace Luoyecb\Ini;

interface IniParserInterface {
	function get(string $key, $section);

	function getSection(string $section): array;

	function getAll(): array;

	function set(string $key, $val, $section);

	function saveAsString(): string;

	function dotSplit(string $key): array;

	function exists(string $key, $section): bool;
}
