<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home | {{config('app.name')}}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <style type="text/css">
            .dual-list .list-group {
                margin-top: 8px;
            }

            .list-left li, .list-right li {
                cursor: pointer;
            }

            .list-arrows {
                padding-top: 100px;
            }

            .list-arrows button {
                margin-bottom: 20px;
            }
            .form-inline .form-control{
                width: 100%;
            }
            .list-group{
                max-height: 350px;
                overflow-y: scroll;
            }
            .well{
                min-height: 320px;
            }
            .delete_item{
                color: red;
                cursor: pointer;
            }
        </style>
        <!-- Scripts -->
        <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    </head>
    <body>
        <div class="container" id="container">
            <br />
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2>Item Management Page</h2>
                </div>
            </div>
            <br/>
            <div id="displayItemList"></div>
            
        </div>
        

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/react.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/JSXTransformer.js"></script>
    <script type="text/jsx"> 
        //alert(document.getElementById("displayItemList"));
        var ItemComponent = React.createClass({
            getInitialState: function () {
                return {left: null, right: null, disableLeft: false, disableRight: false, leftSearch: "", rightSearch: "", disableLeftClear: true, disableRightClear: true};
            },
            componentDidMount : function(){                 
                $.ajax({
                  url: "{{url('api/get_items')}}",
                  type: "GET"})
                  .done(function(response){  
                      if(response.LEFT){
                            var left = Object.values(response.LEFT);
                            //console.log(data);
                            this.setState({
                                left:left
                            });
                      }
                      if(response.RIGHT){
                            var right = Object.values(response.RIGHT);
                            //console.log(data);
                            this.setState({                                
                                right:right
                            });
                      }
                    //alert(this.state.data)
                }.bind(this));				
            },
            selectLeftItem: function(item_id, argument){
                $('.list-group-item').removeClass('active');
                $('.list-group-item[id="'+item_id+'"]').addClass('active');
                this.setState({ disableLeft: true, disableRight: false});
            },
            selectRightItem: function(item_id, argument){
                $('.list-group-item').removeClass('active');
                $('.list-group-item[id="'+item_id+'"]').addClass('active');
                this.setState({ disableLeft: false, disableRight: true});
            },
            moveItem: function(postion, argument){
                actives = $('ul li.active');                
                var item_id = actives.attr('id');  
                if(item_id !== undefined){
                    $.ajax({
                      url: "{{url('api/get_items')}}/" + item_id,
                      type: "POST",
                      data: {position : postion }
                      })
                      .done(function(response){  
                        if(response.error == 0){
                            if(response.data.LEFT){
                            var left = Object.values(response.data.LEFT);
                            //console.log(data);
                            this.setState({
                                left:left
                            });
                        }else{
                            this.setState({
                                left:null
                            });
                        }
                        if(response.data.RIGHT){
                          var right = Object.values(response.data.RIGHT);
                          //console.log(data);
                          this.setState({                                
                              right:right
                          });
                        }else{
                            this.setState({
                                right:null
                            });
                        }
                           // alert(response.message);
                        }else{
                            alert(response.message);
                        }
                    }.bind(this));
                    $('.list-group-item').removeClass('active');
                    this.setState({ disableLeft: false, disableRight: false});
                }else{
                    alert("You must select an item to move");
                }
            },
            addItem: function(e){
                var item_name = $('#item').val();
                
                if($.trim(item_name).length == 0){
                    alert("Type item name to add");
                }else{
                    $.ajax({
                      url: "{{url('api/add_item')}}",
                      type: "POST",
                      data: {name : item_name }
                      })
                      .done(function(response){  
                          if(response.error == 0){                            
                            if(response.data.LEFT){
                                var left = Object.values(response.data.LEFT);
                                //console.log(data);
                                this.setState({
                                    left:left
                                });
                            }else{
                                this.setState({
                                    left:null
                                });
                            }
                            if(response.data.RIGHT){
                              var right = Object.values(response.data.RIGHT);
                              //console.log(data);
                              this.setState({                                
                                  right:right
                              });
                            }else{
                                this.setState({
                                    right:null
                                });
                            }
                            $('#item').val("");
                            alert(response.message);
                        }else{
                            alert(response.message);
                        }
                    }.bind(this));
                }
            },
            deleteItem: function(item_id, argument){
                var $itemClass = this;
                $.confirm({
                    title: 'Confirm!',
                    content: "Are you sure to delete?",
                    theme: 'material',
                    buttons: {
                        ok: function () {
                                
                            $.ajax({
                              url: "{{url('/api/delete_item')}}/"+item_id,
                              async: false,
                              type: "DELETE",
                              data: {id: item_id}})
                              .done(function(response){									      	
                                if(response.error == 0){
                                    if(response.data.LEFT){
                                        var left = Object.values(response.data.LEFT);
                                        //console.log(data);
                                        $itemClass.setState({
                                            left:left
                                        });
                                    }else{
                                        $itemClass.setState({
                                            left:null
                                        });
                                    }
                                    if(response.data.RIGHT){
                                      var right = Object.values(response.data.RIGHT);
                                      //console.log(data);
                                      $itemClass.setState({                                
                                          right:right
                                      });
                                    } else{
                                        $itemClass.setState({
                                            right:null
                                        });
                                    }                         

                                    $('#item').val("");
                                    $('.list-group-item').removeClass('active');
                                }else{
                                    alert(response.message);
                                }
                                }.bind(this));
                                           
                        },
                        cancel: function () {
                           // $.alert('Canceled!');
                        }				        
                    }
                });
            },
            leftSearch: function(event) {
                this.setState({ leftSearch: event.target.value,  disableLeftClear: false})
            },
            leftFilterFunction: function(item) {
                return item.name.toUpperCase().indexOf(this.state.leftSearch.toUpperCase()) > -1
            },
            rightSearch: function(event) {
                this.setState({ rightSearch: event.target.value, disableRightClear: false })
            },
            rightFilterFunction: function(item) {
                return item.name.toUpperCase().indexOf(this.state.rightSearch.toUpperCase()) > -1
            },
            clearSearch: function(position, argument){
                if(position == 'left'){
                    this.setState({leftSearch: "", disableLeftClear: true});
                }else{
                    this.setState({rightSearch: "", disableRightClear: true});
                }
            },
            render: function(){
                
                 return (
                         <div>
                            <div className="row">
                                <div className="col-md-10 col-md-offset-1">
                                    <div className="form-inline">                        
                                        <div className="form-group col-md-4 col-md-offset-2">
                                            <label for="item" className="sr-only">Enter Item name and click Add</label>
                                            <input type="text" className="form-control large_text_box" id="item" placeholder="Enter Item name and click Add" />
                                        </div>
                                        <button type="button" className="btn btn-primary col-md-2" onClick={this.addItem.bind(this)}>Add</button>
                                    </div>
                                </div>                
                            </div>
                            <br/>
                            <br/>
                            <div className="row">
                                <div className="col-md-10 col-md-offset-1">
                                    <div className="dual-list list-left col-md-5">
                                        <div className="well">
                                            <div className="row">
                                                <div className="col-md-9">
                                                    <div className="input-group">
                                                        <span className="input-group-addon glyphicon glyphicon-search"></span>
                                                        <input type="text" name="SearchDualList" value={this.state.leftSearch} className="form-control left-search" placeholder="search" onChange={this.leftSearch}/>
                                                    </div>
                                                </div>
                                                <div className="col-md-2">
                                                    <div className="btn-group">
                                                        <button type="button" className="btn btn-primary" disabled={this.state.disableLeftClear} onClick={this.clearSearch.bind(this, 'left')}><span className="glyphicon glyphicon-remove"></span></button> 
                                                    </div>                                                    
                                                </div>
                                            </div>
                                            {(this.state.left != null)  ?
                                                <ul className="list-group left-list-group">
                                                {this.state.left.filter(this.leftFilterFunction).map((item, i) => 
                                                <li className="list-group-item" id={item.id} onClick={this.selectLeftItem.bind(this, item.id)}>
                                                    <div className="row">
                                                        <div className="col-md-6">{ item.name }</div> 
                                                        <div className="text-right col-md-6" title="Delete Item" ><i className="glyphicon glyphicon-trash delete_item " onClick={this.deleteItem.bind(this, item.id)}></i></div>
                                                    </div>
                                                </li>
                                                )}
                                                </ul>
                                            :
                                                <div> 
                                                    <br/>
                                                    <p className="text-center"><span className="glyphicon glyphicon-info-sign"></span> No data found</p> 
                                                </div>
                                            }
                                        </div>
                                    </div>
                                    <div className="list-arrows col-md-1 text-center">
                                        <button className="btn btn-default btn-sm move-left" onClick={this.moveItem.bind(this, "LEFT")} disabled = {(this.state.disableLeft)}>
                                            <span className="glyphicon glyphicon-chevron-left"></span>
                                        </button>

                                        <button className="btn btn-default btn-sm move-right" onClick={this.moveItem.bind(this, 'RIGHT')} disabled = {(this.state.disableRight)}>
                                            <span className="glyphicon glyphicon-chevron-right"></span>
                                        </button>
                                    </div>
                                    <div className="dual-list list-right col-md-5">
                                        <div className="well">
                                            <div className="row">
                                                <div className="col-md-9">
                                                    <div className="input-group">
                                                        <span className="input-group-addon glyphicon glyphicon-search"></span>
                                                        <input type="text" name="SearchDualList" value={this.state.rightSearch}  className="form-control left-search" placeholder="search" onChange={this.rightSearch}/>                                                        
                                                    </div>
                                                </div>
                                                <div className="col-md-2">
                                                    <div className="btn-group">
                                                        <button type="button" className="btn btn-primary" disabled={this.state.disableRightClear} onClick={this.clearSearch.bind(this, 'right')}><span className="glyphicon glyphicon-remove"></span></button> 
                                                    </div>                                                    
                                                </div>
                                            </div>
                                            {(this.state.right )  ?
                                                <ul className="list-group right-list-group">
                                                {this.state.right.filter(this.rightFilterFunction).map((item, i) => 
                                                     <li className="list-group-item" id={ item.id } onClick={this.selectRightItem.bind(this, item.id)}>
                                                     <div className="row">
                                                        <div className="col-md-6">{ item.name }</div> 
                                                        <div className="text-right col-md-6" title="Delete Item" ><i className="glyphicon glyphicon-trash delete_item " onClick={this.deleteItem.bind(this, item.id)}></i></div>
                                                    </div>
                                                     </li>
                                                )}
                                                </ul>
                                            :
                                                <div> 
                                                    <br/>
                                                    <p className="text-center"><span className="glyphicon glyphicon-info-sign"></span> No data found</p> 
                                                </div>
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
            );
            }
        });
        React.render(<ItemComponent/>,document.getElementById("displayItemList"));
    </script>
    <script type="text/javascript">
        $(function () {
            $('[name="SearchDualList"]').keyup(function (e) {
                var code = e.keyCode || e.which;
                if (code == '9')
                    return;
                if (code == '27')
                    $(this).val(null);
                var $rows = $(this).closest('.dual-list').find('.list-group li');
                var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                $rows.show().filter(function () {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    return !~text.indexOf(val);
                }).hide();
            });
        });
    </script>
    </body>
</html>
