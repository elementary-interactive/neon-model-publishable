# NEON &mdash; Model Publishable
Handles any model to became publishable.

## Requirements
* `"neon/model-uuid": "^1.0"`

## Install
Easily install the composer package:
```bash
composer require neon/model-publishable
```

## Usage
### Database
In the database, Publishable needs two datetime fields:
* published_at
* expired_at
Both of them nullable with the default value NULL.

### Model
In the model we just should apply Publishable Trait like this:
```
use Neon\Model\Traits\Publishable;

class AwesomeModel extends Model
{
    use Publishable;
}
```

Then you can use some common method, like:
* To publish a model, there is ```$model->publish()``` method.
* If you have to publish in a certain time, you can use ```$model->publishedAt($timestamp)``` method.
* To make it expire you can use ```$model->expire()``` and ```$model->expiredAt($timestamp)``` method.

The `Published` scope automatically being applied to the model. If you would like to get all the models, you can query with `->withNotPublished()` method then the scope will not applied.

#### Events
This trait add some new Eloquent Model event:
* `publishing` will be called after `publish()` or `publishedAt()` method called but before save.
* After save will be `published` event fired.
* For expiring there are `expiring` and `expired` methods.

<!-- ## How It Works?

It's so easy basically. The "variables", a.k.a. attributes stored in database in the `attributes` table. -->

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.