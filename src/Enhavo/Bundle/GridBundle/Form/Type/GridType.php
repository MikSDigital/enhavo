<?php
/**
 * GridType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Item\ItemTypeResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class GridType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var ItemTypeResolver
     */
    protected $resolver;

    public function __construct(ObjectManager $manager, ItemTypeResolver $resolver)
    {
        $this->resolver = $resolver;
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('items', 'collection', array(
            'type' => 'enhavo_grid_item',
            'allow_delete' => true,
            'allow_add'    => true,
            'by_reference' => false
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $items = array();
        if(count($options['items'])) {
            foreach($options['items'] as $item) {
                $definition = $this->resolver->getDefinition($item);
                $items[] = array(
                    'type' => $definition->getName(),
                    'label' => $definition->getLabel(),
                    'translationDomain' => $definition->getTranslationDomain()
                );
            }
        } else {
            foreach($this->resolver->getItems() as $item) {
                $items[] = array(
                    'type' => $item->getName(),
                    'label' => $item->getLabel(),
                    'translationDomain' => $item->getTranslationDomain()
                );
            }
        }
        $view->vars['items'] = $items;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\GridBundle\Entity\Grid',
            'items' => array()
        ));
    }

    public function getName()
    {
        return 'enhavo_grid';
    }
}
