# CategoryHierarchy Helper for Laravel

If you're using a single entity to map parent / child relationships, it can be difficult to represent that data visually in your views. This helper will easily display a nested hierarchy of entities that have a child/parent relationship on themself. This can be useful for displaying category navigation in applications.

# Example scenario 

Let's say we have a restaurant application, and we want to offer a selection of different types of food (we'll call these our products) that is available for either delivery or takeout. With different types of cuisine only available under their relevant category (for example, Japanese food may be available for takeout, but not home delivery). 

We can represent this with the following table schema:

| id    | name           | parent_id  |
| ----- |:--------------:| ----------:|
| 1     | Takeout menu   | 0          |
| 2     | Delivery       | 0          |
| 3     | Chinese        | 1          |
| 4     | Indian         | 1          |
| 5     | Burgers        | 1          |
| 6     | Japanese       | 2          |
| 7     | Italian        | 2          |
| 8     | Pizza          | 7          |
| 9     | Pasta          | 7          |

    
We can then create a Product model which represents each type of dish we want to offer, which would have the following relationships defined:
    
    namespace App;

    class Product extends Model {
    
        public function parent()
        {
            return $this->hasOne('App\Product', 'id', 'parent_id');
        }

        public function children()
        {
            return $this->hasMany('App\Product', 'parent_id', 'id');
        }
    
## The problem

What we want to now do is visually represent this data, but Laravel doesn't offer a built-in method for representing these types of relationship within views. Typically, this would be nicely displayed using lists.

## Example output

    <ul>
      <li>Takeout Menu</li>
          <ul>
              <li>Chinese</li>
              <li>Indian</li>
              <li>Burgers</li>
          </ul>
      <li>Delivery</li>
        <ul>
            <li>Japanese</li>
            <li>Italian</li>
                <ul>
                    <li>Pizza</li>
                    <li>Pasta</li>
                </ul>
        </ul>
    </ul>

## The solution

This is exactly where this helper class comes in!

## Additional features

The hierarchy can optionally output checkboxes, with the value set to the ID of the category. If the helper class is passed values which have an additional 'checked' value, then the checkboxes that are created are set to checked. This can be useful for editing category assignments.

## Installation

Simply copy the 'helpers' directory into your 'app' folder and you're done! *Note:* the default namespace for the files is *App*. You can change this to suit your application.

## Usage

In your controller, reference the helper class using:

    use App\Helpers\CategoryHierarchy;
    
Then, in your methods you can instantiate the class using:

    $products = $product->getAllProducts();
    $hierarchy->setupItems($products);
    return $hierarchy->render();
    
From this example, you can see that the `setupItems` method accepts a collection of data. It expects each element to have an `id`, `parent_id`, and `name`.    

And that's all there is to it!
