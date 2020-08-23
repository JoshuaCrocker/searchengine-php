<?php


namespace Crockerio\SearchEngine\Engine\Filter;


class DuplicateFilter implements IFilter
{
    public function filter($results)
    {
        $output = [];
        $ids = [];
        
        foreach ($results as $r) {
            var_dump($r->domain->id);
            if (!in_array($r->domain->id, $ids)) {
                $ids[] = $r->domain->id;
                $output[] = $r;
            }
        }
        
        return collect($output);
    }
}
