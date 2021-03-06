<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilter;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;

class TextFilter extends AbstractFilter
{
    public function render($options, $value)
    {
        $template = $this->getOption('template', $options, 'EnhavoAppBundle:Filter:text.html.twig');

        return $this->renderTemplate($template, [
            'type' => $this->getType(),
            'value' => $value,
            'label' => $this->getOption('label', $options, ''),
            'translationDomain' => $this->getOption('translationDomain', $options, null),
            'icon' => $this->getOption('icon', $options, ''),
            'name' => $this->getRequiredOption('name', $options),
        ]);
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $property = $this->getRequiredOption('property', $options);
        $joinProperty = [];
        if(substr_count($property, '.') >= 1){
            $exploded = explode('.', $property);
            foreach ($exploded as $piece) {
                if(count($exploded) > 1){
                    $joinProperty[] = array_shift($exploded);
                } elseif (count($exploded) === 1) {
                    $property = array_shift($exploded);
                }
            }
        }
        
        $operator = $this->getOption('operator', $options, FilterQuery::OPERATOR_LIKE);
        
        if($value) {
            $query->addWhere($property, $operator, $value, $joinProperty ? $joinProperty : null);
        }
    }

    public function getType()
    {
        return 'text';
    }
}