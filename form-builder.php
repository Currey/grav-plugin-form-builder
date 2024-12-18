<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Grav;
use Grav\Common\Page\Interfaces\PageInterface;
use Grav\Common\Plugin;
use Grav\Common\Uri;
use Grav\Events\FlexRegisterEvent;
use Grav\Framework\Flex\Interfaces\FlexDirectoryInterface;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Plugin\Form\Form;
use RocketTheme\Toolbox\Event\Event;
use Twig\TwigFunction;
use Twig\TwigFilter;

/**
 * Class FormBuilderPlugin
 * @package Grav\Plugin
 */
class FormBuilderPlugin extends Plugin
{
    public $features = [
        'blueprints' => 0,
    ];

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                // Uncomment following line when plugin requires Grav < 1.7
                // ['autoload', 100000],
                ['onPluginsInitialized', 0]
            ],
            FlexRegisterEvent::class => [
                ['onRegisterFlex', 0]
            ],
            'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 1],
            'onTwigExtensions'      => ['onTwigExtensions', 0],
            'onFlexAfterSave'       => ['onFlexAfterSave', 0],
            // 'onPagesInitialized'    => ['onPagesInitialized', 0],
        ];
    }

    /**
     * Composer autoload
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {

            $this->grav['assets']->addJs('plugin://form-builder/assets/admin/form-builder.js', ['group' => 'bottom', 'loading' => 'defer', 'priority' => -5]);

            return;
        }

        // Enable the main events we are interested in
        $this->enable([
          'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 1],
          'onGetPageTemplates' => ['onGetPageTemplates', 0],
        ]);
    }

    /**
     * @param Event $event
     * @return void
     */
    public function onGetPageTemplates(Event $event): void
    {
        /** @var Types $types */
        $types = $event->types;
        $types->register('form-builder');
    }

    public function onRegisterFlex($event): void
    {
        $flex = $event->flex;

        $flex->addDirectoryType(
            'form-builder',
            'blueprints://flex-objects/form-builder.yaml'
        );

    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths() : void
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onTwigExtensions()
    {
        $this->grav['twig']->twig->addFunction(
            new TwigFunction('form_builder', [FormBuilderPlugin::class, 'getForm'])
        );
        $this->grav['twig']->twig->addFilter(
            new TwigFilter('get_form_builder', [FormBuilderPlugin::class, 'getForm'])
        );
    }

    /**
     * this fixes a bug of when you edit the storage key (you get a 404) because the page moved
     */
    public function onFlexAfterSave($event)
    {
        $obj = $event['object'];
        // only handle our type
        if( $obj->getType() !== 'form-builder') {
            return;
        }

        $uri = $this->grav['uri'];

        if ($obj->getKey() === $obj->getStorageKey()) {
            return;
        }

        $paths = $uri->paths();
        if (isset($paths[2])) {
            $paths[2] = $obj->getStorageKey();
            $this->grav->redirect(implode('/', $paths), 302);
        }
    }

    // /**
    //  * hook into page routing to place our detail pages
    //  */
    // public function onPagesInitialized($event)
    // {
    //     $current = Uri::getCurrentRoute();
    //     $route = $current->getRoute();
    //     $this->simpleRouting($route);
    //     // $page = Grav::instance()['page'];
    //     // $formKey = $page->getNestedProperty('header.flex.directory');
    //     // $page->addForms($this->getForm($formKey));
    // }

    /**
     * @return array
     *
     * Collate Form-Builder Flex directories for a select field
     *      using data-options@.
     */
    public static function listForms(): array
    {
        $directory = Grav::instance()->get('flex')->getDirectory('form-builder');

        if ($directory) {
            $collection = $directory->getCollection();

            if (!$collection) {
                return [];
            }

            $objects = $collection->toArray();
            $result = [];

            foreach ($objects as $object) {
                $result[$object->getStorageKey()] = $object->getNestedProperty('header.form.title');
            }

            return $result;
        }
    }

    /**
     * @return object
     *
     * Collate Form-Builder object data to match FormInterface
     *     object for rendering.
     */
    public static function getForm(string $formKey): ?object
    {
        $directory = Grav::instance()->get('flex')->getDirectory('form-builder');

        if ($directory) {
            $object = $directory->getObject($formKey);

            if ($object) {

                $page = Grav::instance()['page'];

                // // Use Grav's Form class to create a proper form object
                // $formData = [
                //     'form' => $object->getNestedProperty('header.form'),
                //     'name' => $object->getNestedProperty('header.form.name'),
                //     'id' => $object->getNestedProperty('header.form.id') ?? $object->getNestedProperty('header.form.name'),
                //     'fields' => $object->getNestedProperty('header.form.fields'),
                //     'buttons' => $object->getNestedProperty('header.form.buttons'),
                //     'form_button_outer_classes' => $object->getNestedProperty('header.form.form_button_outer_classes'),
                //     'process' => $object->getNestedProperty('header.form.process'),
                //     'method' => $object->getNestedProperty('header.form.method', 'POST'),
                //     'action' => $object->getNestedProperty('header.form.action'),
                //     'classes' => $object->getNestedProperty('header.form.classes'),
                // ];
                // Use Grav's Form class to create a proper form object
                $formData = $object->getNestedProperty('header.form');

                // $page->addForms($formData);
                return new Form($page, $formData['name'], $formData);
                // return $formData;
                // return new Form(Grav::instance(), $formData);
            }
        }

        return null;
    }
}
