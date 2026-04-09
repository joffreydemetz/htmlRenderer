# HTML Renderer

Data-driven HTML element builder designed for use with template engines like Twig. Provides renderable components (buttons, pagers, spans) with attribute management, positioning, and a `toData()` method that outputs arrays ready for template consumption.

## Installation

```bash
composer require jdz/htmlrenderer
```

## Requirements

- PHP >= 8.2

## Usage

### Button

```php
use JDZ\Renderer\Button;

$btn = new Button('save', 'a', 'https://example.com', 'blank');
$btn->setText('Save')
    ->setIcon('fa fa-save')
    ->setTip('Save changes')
    ->addStyle('btn-primary');

$data = $btn->toData();
// [
//   'renderer' => 'button',
//   'name'     => 'save',
//   'tag'      => 'a',
//   'text'     => '<span class="fa fa-save"></span> Save',
//   'attrs'    => [
//     'class'          => 'btn-primary',
//     'href'           => 'https://example.com',
//     'target'         => '_blank',
//     'title'          => 'Save changes',
//     'data-toggle'    => 'tooltip',
//     'data-placement' => 'bottom',
//   ],
// ]
```

### Span

```php
use JDZ\Renderer\Span;

$span = new Span('Hello World');
$span->addStyle('highlight')
     ->addDataAttr('id', 42);

$data = $span->toData();
// ['renderer' => 'span', 'text' => 'Hello World', 'attrs' => ['class' => 'highlight', 'data-id' => 42]]
```

### Pager

```php
use JDZ\Renderer\Pager;

$pager = new Pager(10); // max 10 visible pages
$pager->setPrevText('Previous');
$pager->setNextText('Next');
$pager->load(25, 3, 'https://example.com/list');

$data = $pager->toData();
// ['renderer' => 'pager', 'pages' => [...], 'attrs' => [...]]
// Each page: ['renderer' => 'button', 'tag' => 'a', 'text' => '3', 'attrs' => ['href' => '...?page=3', 'class' => 'active']]
```

Without a base URL, pages get `data-page` attributes instead of `href` for JavaScript handling:

```php
$pager = new Pager();
$pager->load(10, 1);
// Pages have: 'attrs' => ['data-page' => 1]
```

### Element Collection (ElementsTrait)

Manage ordered collections of elements with flexible positioning:

```php
use JDZ\Renderer\Button;
use JDZ\Renderer\ElementsTrait;

// In your toolbar/container class:
class Toolbar {
    use ElementsTrait;
}

$toolbar = new Toolbar();
$toolbar->addElement(new Button('save'));    // position 1
$toolbar->addElement(new Button('cancel'));  // position 2
$toolbar->addElement(new Button('delete'));  // position 3

// Reposition elements
$toolbar->setElementPosition('delete', 'first');
$toolbar->setElementPositionAfter('cancel', 'delete');
$toolbar->setElementPositionBefore('save', 'cancel');
```

### Attributes (Data, ARIA, Styles)

All renderable components support data attributes, ARIA attributes, and CSS classes:

```php
$btn = new Button('menu');
$btn->setText('Menu');

$btn->addDataAttr('toggle', 'dropdown');
$btn->addAriaAttr('expanded', 'false');
$btn->addStyles('btn btn-default dropdown-toggle');

$data = $btn->toData();
// attrs: ['class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown', 'aria-expanded' => 'false', 'href' => '#']
```

## Twig Integration

The `toData()` output is designed for Twig templates:

```twig
{# button.html.twig #}
<{{ button.tag }}{% for key, value in button.attrs %} {{ key }}="{{ value }}"{% endfor %}>
  {{ button.text | raw }}
</{{ button.tag }}>
```

```twig
{# pager.html.twig #}
<nav>
  <ul>
    {% for page in pager.pages %}
      <li{% if page.attrs.class is defined %} class="{{ page.attrs.class }}"{% endif %}>
        <a{% for key, value in page.attrs %}{% if key != 'class' %} {{ key }}="{{ value }}"{% endif %}{% endfor %}>
          {{ page.text }}
        </a>
      </li>
    {% endfor %}
  </ul>
</nav>
```

## Testing

```bash
composer test
# or
vendor/bin/phpunit
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
