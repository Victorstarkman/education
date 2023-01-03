 - [To get the SQL query using](#ExamplesgetQuery)
 - [To get the SQL Run](#ExamplesRun)

# ExamplesgetQuery

- ## Exemple 1
        ### Comant
            ```
                use select::Select;
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->getQuery();
                or 
                $select->run();
            ```
        ### Result
            ```
                SELECT id, nameuser FROM users
            ```
    
- ## Exemple 2
        ### Comant
            ```
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->setWhere(['id' => 1], ['=']);
                $select->getQuery();
            ```
        ### Result
            ```
                SELECT id, nameuser FROM users WHERE id = :idGenericSQLFormatWhere 
            ```

- ## Exemple 3
        ### Comant
            ```
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->setWhere(['id' => 1,'nameuser'=>'teste'], ['=','like'], ['AND']);
                $select->getQuery();
            ```
        ### Result
            ```
                SELECT id, nameuser FROM users WHERE id = :idGenericSQLFormatWhere AND  nameuser like :nameuserGenericSQLFormatWhere 
            ```

- ## Exemple 4 
        ### Comant
            ```
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->setWhere(['id' => 1,'nameuser'=>'teste'], ['nameuser'=>'=','id'=>'like'], ['OR']);
                $select->getQuery();
            ```
        ### Result
            ```
                SELECT id, nameuser FROM users WHERE nameuser = :nameuserGenericSQLFormatWhere OR  id like :idGenericSQLFormatWhere 
            ```

- ## Exemple 5
        ### Comant
            ```
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->setWhere(['id' => 1,'nameuser'=>'teste'], ['=','like'], ['OR']);
                $select->setOrder(['id']);
                $select->getQuery();
            ```
        ### Result
            ```
                SELECT id, nameuser FROM users WHERE nameuser = :nameuserGenericSQLFormatWhere OR  id like :idGenericSQLFormatWhere  ORDER BY id
            ```    

- ## Exemple 6
        ### Comant
            ```
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->setWhere(['id' => 1,'nameuser'=>'teste'], ['=','like'], ['OR']);
                $select->setOrder(['id']);
                $select->getQuery();
            ```
        ### Result
            ```
                SELECT id, nameuser FROM users WHERE nameuser = :nameuserGenericSQLFormatWhere OR  id like :idGenericSQLFormatWhere  LIMIT 1
            ```

- ## Exemple 7
        ### Comant
            ```
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->setWhere(['id' => 1,'nameuser'=>'teste'], ['=','like'], ['OR']);
                $select->setGroup(['id']);
                $select->getQuery();
            ```
        ### Result
            ```
                SELECT id, nameuser FROM users WHERE nameuser = :nameuserGenericSQLFormatWhere OR  id like :idGenericSQLFormatWhere  GROUP BY id
            ```

- ## Exemple 8
        ### Comant
            ```
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->setWhere(['id' => 1,'nameuser'=>'teste'],['nameuser'=>'=','id'=>'like'], ['OR']);
                $select->setGroup(['id']);
                $select->setOrder(['id']);
                $select->setLimit(1);
                $select->getQuery();
            ```
        ### Result
            ```
                SELECT id, nameuser FROM photos.users WHERE nameuser = :nameuserGenericSQLFormatWhere OR  id like :idGenericSQLFormatWhere  GROUP BY id ORDER BY id LIMIT 1
            ```

# ExamplesRun

- ## Abstract

        All examples in getQuery execute normally in run makes the query in the database and getQuery shows the assembled query.

- ## Exemple
        ### Comant
            ```
                use select::Select;
                $select = new Select(true);
                $select->setFrom('users');
                $select->setColumns(['id', 'nameuser']);
                $select->setWhere(['id' => 1], ['=']);
                $select->run();
            ```
        ### Result
            ```
               [0] => Array
                ( 
                    [id] => 1
                    [nameuser] => teste
                )

            ```

