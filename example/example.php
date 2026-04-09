<?php

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use JDZ\Renderer\Button;
use JDZ\Renderer\Span;
use JDZ\Renderer\Pager;

// ========================================
// Button
// ========================================

echo "=== Button (anchor) ===" . PHP_EOL;

$btn = new Button('save', 'a', 'https://example.com', 'blank');
$btn->setText('Save')
    ->setIcon('fa fa-save')
    ->setTip('Save changes')
    ->addStyle('btn-primary');

print_r($btn->toData());

echo PHP_EOL . "=== Button (submit) ===" . PHP_EOL;

$submit = new Button('submit', 'button');
$submit->setText('Submit')
    ->addStyle('btn')
    ->addStyle('btn-success')
    ->setWidth(200);

print_r($submit->toData());

// ========================================
// Span
// ========================================

echo PHP_EOL . "=== Span ===" . PHP_EOL;

$span = new Span('Hello World');
$span->addStyle('highlight')
    ->addDataAttr('id', 42)
    ->addAriaAttr('label', 'Greeting');

print_r($span->toData());

// ========================================
// Attributes
// ========================================

echo PHP_EOL . "=== Attributes ===" . PHP_EOL;

$menu = new Button('menu');
$menu->setText('Menu');
$menu->addDataAttr('toggle', 'dropdown');
$menu->addAriaAttr('expanded', 'false');
$menu->addStyles('btn btn-default dropdown-toggle');

print_r($menu->toData());

// ========================================
// Pager with URLs
// ========================================

echo PHP_EOL . "=== Pager (with URLs) ===" . PHP_EOL;

$pager = new Pager(10);
$pager->setPrevText('Previous');
$pager->setNextText('Next');
$pager->load(8, 3, 'https://example.com/list');

$data = $pager->toData();
echo "Total pages rendered: " . count($data['pages']) . PHP_EOL;
foreach ($data['pages'] as $page) {
    $class = $page['attrs']['class'] ?? '';
    $href = $page['attrs']['href'] ?? '';
    echo "  [{$page['text']}] class=\"{$class}\" href=\"{$href}\"" . PHP_EOL;
}

// ========================================
// Pager without URLs (JavaScript mode)
// ========================================

echo PHP_EOL . "=== Pager (JavaScript mode) ===" . PHP_EOL;

$pager2 = new Pager();
$pager2->load(5, 2);

$data2 = $pager2->toData();
foreach ($data2['pages'] as $page) {
    $class = $page['attrs']['class'] ?? '';
    $dataPage = $page['attrs']['data-page'] ?? '';
    echo "  [{$page['text']}] class=\"{$class}\" data-page=\"{$dataPage}\"" . PHP_EOL;
}

// ========================================
// Large pager with ellipsis
// ========================================

echo PHP_EOL . "=== Large Pager (30 pages, current=15) ===" . PHP_EOL;

$pager3 = new Pager(10);
$pager3->load(30, 15);

$data3 = $pager3->toData();
foreach ($data3['pages'] as $page) {
    $class = $page['attrs']['class'] ?? '';
    echo "  [{$page['text']}] class=\"{$class}\"" . PHP_EOL;
}
