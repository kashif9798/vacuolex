<div
  class="modal fade text-left"
  id="deleteCategory{{ $category->id }}"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Category</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the category <strong>{{ $category->title }}</strong>?
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route("categories.destroy", $category) }}">
          @csrf
          @method("DELETE")
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>