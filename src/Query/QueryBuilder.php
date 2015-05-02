<?php

namespace Reduce\Db\Query;

use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;

class QueryBuilder extends DBALQueryBuilder
{
    public function where($predicate, $value = null)
    {
        if ($value) {
            $this->andWhere($predicate);
            $this->createPositionalParameter($value);
            return $this;
        }
        
        if (is_array($predicate)) {
            foreach ($predicate as $key => $value) {
                $this->where($key, $value);
            }

            return $this;
        }

        $this->andWhere($predicate);
        
        return $this;
    }    
}