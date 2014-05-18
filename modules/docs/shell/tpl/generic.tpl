<div style="margin-bottom: 30px">
    {if "{$user.id}" != "false"}
        <div style="position: fixed; right: 10px; margin-top: 10px">
            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#edit">
                Editar
            </button>
        </div>
        
        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width: 1260px">
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="myModalLabel">Edição de conteúdo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <textarea style="width: 100%; height: 550px; border: 1px solid #CCC" id="editor">{$page.buffer}</textarea>
                            </div>
                            <div class="col-md-7 preview" id="preview">
     
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                      <button type="button" class="btn btn-primary" id="save">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    {endif} 
        
    {if "{$title}" != "false" && "{$description}" != "false"}
    <div style="background-image: url('{$route.root}modules/docs/shell/img/backgound.png'); padding: 30px 15px 40px;">
        <div class="container">
            <h1 style="color: #fff; font-size: 60px; margin-right: 380px; margin-left: 20px">{$title}</h1>
            <p style="font-weight: 300; line-height: 1.5; color: #EFEFEF; font-size: 20px; margin-right: 380px; margin-left: 20px">{$description}</p>
        </div>
    </div>
    {endif}
    
    <div class="container docsGeneric">
        {if {$has.headers}}
        <div class="col-md-3">
            <div class="bs-sidebar hidden-print">
                <ul class="nav bs-sidenav">
                    {list:headers}
                    <li><a href="#{list.headers.id}">{list.headers.value}</a></li>
                    {end}
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            {$dynamic.contents}
        </div>
        {else}
        <div class="col-md-12">
            {$dynamic.contents}
        </div>    
        {endif}
    </div>
</div>