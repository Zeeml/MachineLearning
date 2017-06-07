# MachineLearning
Machine Learning Library in PHP

```
$ml = new ML(
    [LinearRegression::class], 
    Dataset::factory('/path/to/csv'),
    0.7);

$ml->fit();

$ml->test();

$ml->stats();

$ml->predict(Dataset::factory('/path/to/other.csv'));
```
