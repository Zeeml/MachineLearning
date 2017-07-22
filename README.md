![build](https://travis-ci.org/Zeeml/MachineLearning.svg?branch=master)

# MachineLearning
Machine Learning Library in PHP

```
$ml = new ML(Dataset::factory('/path/to/csv'));

$ml
    ->using([LinearRegression::class, LogisticRegression::class])
    ->epochs(12)
    ->fit()
;

$ml
    ->using([KNearestNeighbors::class])
    ->epochs(2)
    ->fit()
;

$ml->test();

$ml->stats();

$ml->predict(LinearRegression::class, Dataset::factory('/path/to/newCsv'));
```
