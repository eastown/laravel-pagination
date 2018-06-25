# laravel-pagination
Pagination for laravel

## Installation

```shell
composer require eastown/laravel-pagination dev-master
```

## Example Models

``\App\User``

* id
* name
* score
* group_id (group belongsTo ``\App\UserGroup``)

``\App\UserGroup``

* id
* name

## Usage
```php
$pagination = (new Eastown\Pagination\Pagination(new \App\User()))
       ->setCurrentPage(1)
       ->setPageSize(20)
       ->conditions([
          new Condition('id', \Eastown\Pagination\Operator::GTE, 100),
          new Condition('id', \Eastown\Pagination\Operator::LTE, 10000),
      ])
      // Simple way
      // ->conditions([
      //          ['id', 'GTE', 100],
      //          ['id', 'LTE', 100],
      // ])
       ->selects([
           new \Eastown\Pagination\Select('SUM(score)', 'total_score'),
           new \Eastown\Pagination\Select('id'),
           new \Eastown\Pagination\Select('name'),
       ])
       // Simple way
       // ->selects([
       //   ['SUM(score)', 'total_score'],
       //   ['id'], // or just use string 'id',
       //   ['name']
       // ])
       ->groups([
           new \Eastown\Pagination\Group('id')
       ])
       // Simple way
       // ->groups([
       //   'id'
       // ])
       ->sorts([
            new \Eastown\Pagination\Sort('id', \Eastown\Pagination\Sort::SORT_DESC)
       ])
       // Simple way
      // ->sorts([
      //   ['id', 'desc']
      // ])
       ;
       

$total = $pagination->total();
$sum = $pagination->sum(['score']);
$data = $pagination->query();

// with page info
$info = $pagination->paginate();

// Output exp
// [
//    'data': [...],
//    'total': 100,
//    'page_size': 20,
//    'current_page': 1 
// ]


// Map results
$info = $pagination->paginate(function($user){
    $user->token = uniqid();
    return $user;
});
```

## Condition

```php
(new Eastown\Pagination\Pagination(new \App\User()))
       ->setCurrentPage(1)
       ->setPageSize(20)
       ->conditions([
          new Condition('id', \Eastown\Pagination\Operator::GTE, 100),
          new Condition('id', \Eastown\Pagination\Operator::LTE, 10000),
          new Condition('id', \Eastown\Pagination\Operator::IN, [100, 101, 102]),
          new Condition('id', \Eastown\Pagination\Operator::NOT_IN, [201, 202, 203]),
          new Condition('name', \Eastown\Pagination\Operator::LIKE, '%aaa%'),
          new Condition('name', \Eastown\Pagination\Operator::NOT_LIKE, '%bbb%'),
          new Condition('score', \Eastown\Pagination\Operator::BETWEEN, [1, 100]),
          new Condition('score', \Eastown\Pagination\Operator::NOT_BETWEEN, [500, 1000]),
          new Condition('name', \Eastown\Pagination\Operator::REGEXP, '*'),
          new Condition('group', \Eastown\Pagination\Operator::HAS, 
            [
                new Condition('name', \Eastown\Pagination\Operator::EQ, 'test_group')
            ]
          ),
          new Condition('group', \Eastown\Pagination\Operator::DOES_NOT_HAVE, 
              [
                  new Condition('name', \Eastown\Pagination\Operator::EQ, 'test_group2')
              ]
          )
      ])
      ->paginate();
```

Simple way

```php
(new Eastown\Pagination\Pagination(new \App\User()))
       ->setCurrentPage(1)
       ->setPageSize(20)
       ->conditions([
            ['id', 'GTE', 100],
            ['id', 'LTE', 10000],
            ['id', 'IN', [100, 101, 102]],
            ['id', 'NOT_IN', [201, 202, 203]],
            ['name', 'LIKE', '%aaa%'],
            ['name', 'NOT_LIKE', '%bbb%'],
            ['score', 'BETWEEN', [1, 100]],
            ['score', 'NOT_BETWEEN', [500, 1000]],
            ['name', 'REGEXP', '*'],
            ['group', 'HAS', 
                [
                    ['name', 'EQ', 'test_group']
                ]
            ],
            ['group', 'DOES_NOT_HAVE', 
                [
                    ['name', 'EQ', 'test_group2']
                ]
            ],
      ])
      ->paginate();
```

## Total
```php
(new Eastown\Pagination\Pagination(new \App\User()))->conditions(...)->total();

// Output exp
100

```

## Sum
```php
(new Eastown\Pagination\Pagination(new \App\User()))->conditions(...)->sum(['field1', 'field2']);

// Output exp
// [
//     'field1': 100,
//     'field2': 200
// ]

```


