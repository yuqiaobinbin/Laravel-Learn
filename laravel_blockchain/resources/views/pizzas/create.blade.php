@extends('layouts.app')

@section('content')
    <div class="create-pizza wrapper">
        <h1>Create a new pizza</h1>
        <form action="/pizzas" method="POST">
            @csrf
            <label for="name">Your name::</label>
            <input type="text" id="name" name="name">
            <label for="type">Choose your pizza type</label>
            <select name="type" id="type">
                <option value="margarita">Margarita</option>
                <option value="hawaiian">Hawaiian</option>
                <option value="veg supreme">Veg Supreme</option>
                <option value="volcano">Volcano</option>
            </select>
            <select name="base" id="base">
                <option value="cheesy crust">cheesy crust</option>
                <option value="garlic crust">garlic crust</option>
                <option value="thin & crisp">thin & crisp</option>
                <option value="thick">Thick</option>
            </select>
            <fieldset>
                <label>Extra toppings:</label>
                <input type="checkbox" name="toppings[]" value="mushrooms">Mushrooms<br/>
                <input type="checkbox" name="toppings[]" value="pepper">Pepper<br/>
                <input type="checkbox" name="toppings[]" value="garlic">Garlic<br/>
                <input type="checkbox" name="toppings[]" value="olives">Olives<br/>
            </fieldset>
            <input type="submit" value="Order Pizza">
        </form>
    </div>
@endsection
