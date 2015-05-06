var current_selected_id;
var loaded = false;
var back_id;
var parent_of_root;


/*
    Description: draw a map that include all the major fields root nodes. Each field is assigned with an id 
                in the database, and is stored in an array. The array will be used to provide id information for each node
                on the map
    Paremeter: none
    Return: none
*/
function start_of_tree () {
  document.getElementById("waiting").style.visibility="hidden";
  document.getElementById("return_upper").style.visibility="hidden";

  delete_json();
  d3.select("svg").remove();
  document.getElementById("addNodeBtt").style.visibility='hidden';
  var info_box  = document.getElementById("infoPopup");
  //document.getElementById("up").style.visibility='hidden';
  //document.getElementById("down").style.visibility='hidden';
  info_box.style.visibility='hidden';
  document.getElementById("infoPanel").style.visibility='hidden';


    var svg_width = $("#map").width();
    var svg_height = $("#map").height();

    var svg = d3.select("#map").append("svg")
            .attr("height",svg_height+"px").attr("width",svg_width+"px").attr("id","mysvg");

  var field = [{"name":"Humanities", "id":"5514f9bbe4b0f1a65835f968"},{"name":"Social Science", "id":"54f354f5e4b09c842b11509b"},{"name":"Biology", "id":"54f35517e4b09c842b11509d"},
  {"name":"Earth Science", "id":"5514f975e4b0f1a65835f961"},{"name":"Cosmology", "id":"5514f9a0e4b0f1a65835f962"},
              {"name":"Chemistry", "id":"54f35510e4b09c842b11509c"},{"name":"Physics", "id":"5514f95ae4b0f1a65835f960"},{"name":"Math", "id":"5514f9fce4b0f1a65835f970"}];

  var x, y, radius;
  x = y = 0;
  radius = svg_height /18;

  var field_node = svg.selectAll("g").data(field).enter().append("g");
 // var div_width = document.getElementById("map").offsetWidth;
  var circle = field_node.append("circle").
      attr("cx", svg_width/2).attr("cy",function() { 
        y= y + radius*2 + 5;
        return y;
      }).
      attr("r",radius).style("fill","rgb(157,158,152)");

  x = y = 0;


  var text = field_node.append("text").attr("x",svg_width/2 ).attr("y",function() { 
        y= y + radius*2 + 5;
        return y;
      }).
      style("text-anchor", "middle").style("fill","white").
      text(function(d){return d['name'];});

  circle.on("mouseover",function(d){
   var nodeSelection = d3.select(this).style({opacity:'0.7'});
   nodeSelection.transition().duration(250)
          .attr("r", function(){return radius + 20});

  })
  .on('mouseout', function(d){
      var nodeSelection = d3.select(this).style({opacity:'1'});
      nodeSelection.transition().duration(250)
          .attr("r",function(){ return radius });


  });
     
     
  var query_data;

  field_node.on("click", function(d){
    //draw_svg(d['id']);
    current_selected_id = d['id'];
    make_json(d['id']);
  });

}



/*
    Description: Draw a map that includes itself and its child node. The function accept a node id, then the id is used a 
                  json file name to read information from. All the information will be read to a json array then a map will
                  be drawn
    Parameter: id of the parent node that include all the child nodes in the map
    Return: none
*/

function draw_svg(id)
{
      var svg_width = $("#map").width();
    var svg_height = $("#map").height();
  current_parent_id=id;
  document.getElementById("infoPanel").style.visibility='hidden';
  //document.getElementById("up").style.visibility='hidden';
  //document.getElementById("down").style.visibility='hidden';

  document.getElementById("addNodeBtt").style.visibility='visible';
    document.getElementById("return_upper").style.visibility="visible";


  var info_box  = document.getElementById("infoPopup");
  info_box.style.visibility='visible';
  //alert(id);
  //d3.select("img").remove();

  d3.select("svg").remove();
  //var back_btt= d3.select("body").append("img").attr("src","back.jpg").attr("x",5).attr("y",5).style("height","40px").style("width","40px");

  var svg = d3.select("#map").append("svg")
                .attr("height",svg_height+"px").attr("width",svg_width+"px").attr("id","mysvg")
                .append("g").
                call(d3.behavior.zoom().scaleExtent([1, 10]).on("zoom", function () 
                {
                   svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
                }));

 

    var format = d3.format(",d"),
      color = d3.scale.category20c();
  var max_radius = 120, min_radius = 60;
  var pack = d3.layout.pack()
      .sort(null)
      .size([width, height])
      .padding(20.0);


  var scale = d3.scale.linear().domain([ 0.2, 2 ]).range([ 60, 120]);
  

  try
  {
      d3.json(id+".json", function(error, root) 
      {
        var radius;

        document.getElementById("waiting").style.visibility="hidden"
        var node = svg.selectAll(".node")
            .data(pack.nodes(classes(root))
            .filter(function(d) { return !d.children; }))
          .enter().append("g")
            .attr("class", "node")
            .attr("transform", function(d) {
             if(d.root == 1)
             {
                parent_of_root = d.parent_id;
             } 
             return "translate(" + d.x + "," + d.y + ")"; });

        node.append("title")
            .text(function(d) { return d.className ; });

        var circle = node.append("circle")
            .style("fill", function(d) { 
            if (d.up < d.down) {return "rgb(252,43,57)";}
              if (d.up > d.down) {return "rgb(109,189,6)";}
            else return "rgb(157,158,152)";

            //return color(d.packageName); 
            }).style("opacity",0.88);
       
        circle.attr("r",0).transition().duration(500).attr("r", function(d) {  return (d.r);
              /*if(d.r== 0) return min_radius;
              if(d.r < max_radius && d.r > min_radius) return radius = d.r;
              if (d.r > max_radius) {return radius = max_radius;}*/
             });
        node.append("text")
            .style("text-anchor", "middle").style("fill","white")
            .text(function(d) { return d.className.substring(0, d.r / 4); });  

        node.on("mouseover",function(d){
          delete_json();
            $("#infoPopup").fadeOut(100,function(){

              /*$("title").html ="Node title: " + d.className;*/

               var node_id = document.getElementById("node_id");
              node_id.innerHTML = "ID: " + d.id;

              var status = document.getElementById("status");
              if (d.up > d.down) {
                 status.innerHTML = "Status: support (<img src=\"up.jpg\"  height=\"20\" width=\"20\"> : "+ d.up + " |  <img src=\"down.jpg\"  height=\"20\" width=\"20\">: " + d.down + ")";
              }
              if (d.up < d.down) {
                  status.innerHTML = "Status: discredit (<img src=\"up.jpg\"  height=\"20\" width=\"20\"> : "+ d.up + " |  <img src=\"down.jpg\"  height=\"20\" width=\"20\">: " + d.down + ")";
              }
              if (d.up == d.down) 
              {
                  status.innerHTML = "Status: neutral (<img src=\"up.jpg\"  height=\"20\" width=\"20\"> : "+ d.up + " |  <img src=\"down.jpg\"  height=\"20\" width=\"20\">: " + d.down + ")";

              }
              var title = document.getElementById("title");
              title.innerHTML = "Node title: " + d.className;
              var type = document.getElementById("type");
              if(d.category == 0)
              {
                type.innerHTML = "Node type: Parent";
              }
              if (d.category == 1) {
                type.innerHTML = "Node type: Law";
              }
               if (d.category == 2) {
                type.innerHTML = "Node type: Theory";
              }
              

              //position of popup
              var pos_left = d.x + 120;
              var pos_top = d.y + 120;

              $("#infoPopup").css({"left":pos_left,"top":pos_top});
              $("#infoPopup").fadeIn(100);


          });//end of infoPopup


        });
  
        node.on("mouseout",function(d){
          $("#infoPopup").fadeOut(50);
        });


        circle.on("mouseover",function(d){
          var temp = radius;
          var nodeSelection = d3.select(this)
          nodeSelection.transition().duration(250).style({opacity:'0.7'}).attr("r", function(d) {return d.r + 15;}).attr("stroke-width","2").attr("stroke","black");


        })
        .on('mouseout', function(d){
             var nodeSelection = d3.select(this);
             nodeSelection.transition().duration(250)
             .style({opacity:'1'}).attr("r", function(d) {return d.r;}).attr("stroke-width","0");

        });

        var selected_id;

        circle.on("click",function(d){
          delete_json();
          current_selected_id = d.id;
          document.getElementById("infoPanel").style.visibility='visible';
            //document.getElementById("up").style.visibility='visible';
           // document.getElementById("down").style.visibility='visible';
            refresh_current_layer = true;
            selected_id = d.id;
            current_selected_id = d.id;
            //$("#selected_node").html ="Selected Node: " + d.className;
            var node_title = document.getElementById("selected_node");
            node_title.innerHTML = "Selected Node: " + d.className;
            /*var src = document.getElementById("src");
            src.innerHTML = "Source URL: <a href=\""+ (d.src_url) +"\">"+(d.src_url).substring(0,20) + "...</a>" ;
            var img = document.getElementById("img");
            img.innerHTML = "Image URL: <a href=\""+ (d.img_url) +"\">"+(d.img_url).substring(0,20) +"...</a>" ;
            var vid = document.getElementById("vid");
            vid.innerHTML = "Video URL: <a href=\""+ (d.vid_url) +"\">" + (d.vid_url).substring(0,20) +"...</a>" ;
            */
            var type = document.getElementById("type_panel");
              if(d.category == 0)
              {
                type.innerHTML = "Node type: <b>Parent</b>";
               /* document.getElementById("viewNode").style.visibility='visible';
                document.getElementById("nonparent_menu").style.visibility='hidden';
                document.getElementById("viewInfo").style.visibility='hidden';
                document.getElementById("modifyNodeBtt").style.visibility='hidden';*/
                document.getElementById("nonparent_menu").disabled=true;
                document.getElementById("viewNode").disabled=false;
                $("#viewNode").one("click", 
                  function(){delete_json(); loaded = false; make_json_viewChild(d.id); });
                
              }
              if (d.category == 1) {
                type.innerHTML = "Node type: <b>Law</b>";
                /*document.getElementById("viewNode").style.visibility='hidden';
                 document.getElementById("modifyNodeBtt").style.visibility='visible';
                document.getElementById("viewInfo").style.visibility='visible';
               // document.getElementById("nonparent_menu").style.visibility='visible';*/
                document.getElementById("nonparent_menu").disabled=false;
                document.getElementById("viewNode").disabled=true;
                view_info_window(d.id,id);
                 edit_node_window(d.id,id);

              }
               if (d.category == 2) {
                type.innerHTML = "Node type: <b>Theory</b>";
               /*document.getElementById("viewNode").style.visibility='hidden';
                 document.getElementById("modifyNodeBtt").style.visibility='visible';
                document.getElementById("viewInfo").style.visibility='visible';
               //document.getElementById("nonparent_menu").style.visibility='visible';*/
                document.getElementById("nonparent_menu").disabled=false;
                document.getElementById("viewNode").disabled=true;
  
                view_info_window(d.id,id);      
                edit_node_window(d.id,id);
              }

        }); // end on click

        $("#viewInfo").css("cursor","pointer");
        $("#modifyNodeBtt").css("cursor","pointer");

        $("#return_upper").one("click", 
                  function(){
                    delete_json(); 
                    back_id = id;
                    if (parent_of_root == "0")
                      start_of_tree();
                    else
                      return_upper(id);

                });


        document.getElementById("addNodeBtt").addEventListener("click", 
              function(){
                var NWin = window.open("addNode.php?id="+id, "Add node", 'scrollbars=1,height=800,width=600');
                       if (window.focus)
                       {
                         NWin.focus();
                       }     
            }, false);

        $("#return_upper").on("mouseover",function(){

              $("#return_upper").css("cursor","pointer");
              $("#hint").html("Go back");
              var position = $("#return_upper").position();

              var pos_left = position.left + 100;
              var pos_top = position.right + 60;

              $("#hover-title").css({"left":pos_left,"top":pos_top});
              $("#hover-title").fadeIn(100);
        });

        $("#return_upper").on("mouseout",function(d){
           $("#hover-title").fadeOut(50);
        });

        $("#addNodeBtt").on("mouseover",function(){
             $("#addNodeBtt").css("cursor","pointer");
              $("#hint").html("Add new node");
              var position = $("#return_upper").position();

              var pos_left = position.left + 40;
              var pos_top = position.right + 20;

              $("#hover-title").css({"left":pos_left,"top":pos_top});
              $("#hover-title").fadeIn(100);
        });

        $("#addNodeBtt").on("mouseout",function(d){
           $("#hover-title").fadeOut(50);
        });




      }
    );   
  } 
  catch(err)
    {
      start_of_tree();
    }
}



/*
  Description: open a window when a user want to view the node information. The window open viewNode page and include selected node id
                and its parent id in the link


  Parameter: id of selected node, parent id of selected node
  Return: none
*/
function view_info_window(selected_id,parent_id){
  document.getElementById("viewInfo").addEventListener("click", 
              function(){
                var NWin = window.open("viewNode.php?id="+selected_id + "&parent_id="+parent_id, "View node", 'scrollbars=1,height=900,width=1000');
                       if (window.focus)
                       {
                         NWin.focus();
                       }
            
              }, false);
}


/*
  Description: open a window that allow user to modify information of a selected node.The window open editNode page and include selected node id
                and its parent id in the link
  Parameter: id of selected node, parent id of selected node
  Return: none
*/
function edit_node_window(selected_id,parent_id)
{
          document.getElementById("modifyNodeBtt").addEventListener("click", 
              function(){
                               var NWin = window.open("editNode.php?id="+selected_id +"&parent_id="+parent_id, "Add node", 'scrollbars=1,height=800,width=600');
                       if (window.focus)
                       {
                         NWin.focus();
                       }
                     });
}


/*
  Description: The function will draw a map of the previous layer of nodes, thus it allow user to go back withouth refresh browser

  Parameter: id of parent node
  Return: none
*/
 function return_upper(id)
{
  if (back_id == id)
  {

    document.getElementById("waiting").style.visibility="visible";
     $.ajax({
            type: 'POST',
     
            url: "config_map.php?f=get_parent&p=" + id,
            success: function(d){
                console.log("grandparent:" + d);
                var parent_id =  d;
                if(parent_id = parent_of_root)
                  make_json(parent_id);            
             }
          });
   }
}

/*
  Description:  the funciton is called when a user wants to see child nodes of the selected node. It also check if
                the id passed to the function matched with the selected node. Then a json file that stored all the 
                needed info will be created

  Parameter: id of selected node
  Return:none
*/
function make_json_viewChild(id)
{

  if(id == current_selected_id)
  {
    document.getElementById("waiting").style.visibility="visible";
     $.ajax({
            type: 'POST',
     
            url: "config_map.php?f=make_json&p=" + id,
            success: function(d){
            console.log(d);
            draw_svg(id);
             }
          });

     loaded = true;
  }
}

/*
  Description:  the funciton is called when a user wants to see child nodes of the selected node. Then a json file that stored all the 
                needed info will be created. This is an unsafe version of the function above
  Parameter: id of selected node
  Return:none
*/
function make_json(id)
{
  {
    document.getElementById("waiting").style.visibility="visible";
     $.ajax({
            type: 'POST',
     
            url: "config_map.php?f=make_json&p=" + id,
            success: function(d){
            console.log(d);
            draw_svg(id);
             }
          });

     loaded = true;
  }
}


/*
  Description:  the function will delete all the json files exist in the folder
  Parameter: none
  Return: none
*/
function delete_json()
{
  $.ajax({
          type: 'POST',
          url: "config_map.php?f=delete_json&p=",
          success: function(d){
           
           }
        });
}


/*
  Description: When the function is called, thumb_up function in config_map.php will be called to update the database
              that a thumb up is give to the selected node
  Parameter: parent id, and selected id node
  Return: none
*/
function thumb_up(root,id){
  $.ajax({
          type: 'POST',
          url: "config_map.php?f=thumb_up&p=" + id,
          success: function(d){
            make_json(root);

           }
        });
}

/*
  Description: When the function is called, thumb_down function in config_map.php will be called to update the database
              that a thumb down is give to the selected node
  Parameter: parent id, and selected id node
  Return: none
*/
function thumb_down(root,id){
  $.ajax({
          type: 'POST',
          url: "config_map.php?f=thumb_down&p=" + id,
          success: function(d){
          make_json(root);
           }
        });
}


/*
  Description: The recursive finction will read the json file and write information needed for all the node to a json array
  Parameter: the name of the first information read from json file
  Return : a json array that contains all child nodes and inforation of a node
*/
function classes(root) {
  var classes = [];

  function recurse(name, node) {
    if (node.children)
     node.children.forEach(function(child) 
      { recurse(node.name, child); });
    else 
      classes.push({packageName: name, className: node.name, value: node.size, id:node.id, //synopsis:node.synopsis
         src_url:node.src_url, img_url:node.img_url, vid_url:node.vid_url, parent_id:node.parent_id,
         up:node.up, down:node.down, category:node.category, root:node.root});
  }

  recurse(null, root);

  return {children: classes};
}