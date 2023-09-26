const state = {
    
    parent_categories : [],
    child_categories : [],
    selected_categories_from_root : [],
    new_category : {
        name : null,
        parent_id : null,
        is_parent_category : false,
        category_path : null,
        code : null,
        attributes : [
            {name : ''}
        ],
    },
}

export default state;