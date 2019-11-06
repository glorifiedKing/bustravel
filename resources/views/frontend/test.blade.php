@extends('bustravel::frontend.layouts.app')
@section('title', 'Test Page')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="card uper">
  <div class="card-header">
    Add Shows
  </div>
  <div class="card-body">
    
      <form method="post" >
          <div class="form-group">
              @csrf
              <label for="name">Show Name:</label>
              <input type="text" class="form-control" name="show_name"/>
          </div>
          <div class="form-group">
              <label for="price">Show Genre :</label>
              <input type="text" class="form-control" name="genre"/>
          </div>
          <div class="form-group">
              <label for="price">Show IMDB Rating :</label>
              <input type="text" class="form-control" name="imdb_rating"/>
          </div>
          <div class="form-group">
              <label for="quantity">Show Lead Actor :</label>
              <input type="text" class="form-control" name="lead_actor"/>
          </div>
          <button type="submit" class="btn btn-primary">Create Show</button>
      </form>
  </div>
</div>
@endsection
