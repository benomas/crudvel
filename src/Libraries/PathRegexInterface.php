<?php namespace Crudvel\Libraries;
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2019-01-12
 */
use Crudvel\Libraries\PathInspectorInterface;
interface PathRegexInterface
{
    /**
     * execute a patern find in the selected context
     *
     * @return array
     */
    public function finder($patern=null,$dept=null,$property=null);
    /**
     * execute a patern find in the selected context
     *
     * @return array
     */
    public function setSource(PathInspectorInterface $source);
}
