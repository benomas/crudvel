<?php namespace Crudvel\Libraries;
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2019-01-12
 */
interface PathInspectorInterface
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function scanDir();
}
