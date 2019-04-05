<?php

        if($petiAjax){
            require_once "../modelos/adminModels.php";
        }else{
            require_once "./modelos/adminModels.php";
        }   

        class adminController extends adminModels{

            public function add_coord_controller(){
                    
                    $names=mainModel::clean_cadn($_POST['names-reg']);
                    $lastnames=mainModel::clean_cadn($_POST['lastnames-reg']);
                    $email=mainModel::clean_cadn($_POST['email-reg']);
                    $pass1=mainModel::clean_cadn($_POST['password1-reg']);
                    $pass2=mainModel::clean_cadn($_POST['password2-reg']);
                    if($pass1!=$pass2){
                        $alert=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrio un error Inesperado",
                            "Texto"=>"Las contraseñas no Coinciden, intente nuevamente.",
                            "Tipo"=>"error"
                        ];
                    }else{
                        
                        if($email!=""){
                            $consult=mainModel::exe_query_simple("SELECT Acc_email FROM cuenta WHERE Acc_email='$email@ucatolica.edu.co'");
                            $ec = $consult->rowCount();
                        }else{
                            $ec=0;
                        }
                        if($ec>=1){
                            $alert=[
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrio un error Inesperado",
                                "Texto"=>"El Correo Institucional ya esta Registrado.",
                                "Tipo"=>"error"
                            ];
                        }else{

                            $codigo=mainModel::gen_cod_random("CP",8);
                            $clave=mainModel::encryption($pass1);
                            
                            
                            $data_acc=[
                                "codigo"=>"$codigo",
                                "acuenta"=>"$email",
                                "names"=>"$names",
                                "lastnames"=>"$lastnames",
                                "email"=>"$email@ucatolica.edu.co",
                                "pass"=>"$clave",
                                "estado"=>1,
                                "types"=>"Profesor",
                                "photo"=>""
                            ];
                           
                            $save_acc=mainModel::add_account($data_acc);
                            if($save_acc->rowCount()>=1){
                                $data_adm=[
                                    "gp_gp_cod"=>"",
                                    "gp_acc_cod"=>"$codigo",
                                ];
                            
                                $save_adm=adminModels::add_coord_model($data_adm);
                                if($save_adm->rowCount()>=1){
                                    $alert=[
                                        "Alerta"=>"clean",
                                        "Titulo"=>"Profesor Registrado",
                                        "Texto"=>"El Profesor se registro con Exito!.",
                                        "Tipo"=>"success"
                                    ];
                                }else{
                                    mainModel::delete_account("$codigo");
                                    $alert=[
                                        "Alerta"=>"simple",
                                        "Titulo"=>"Ocurrio un error Inesperado",
                                        "Texto"=>"Los datos de Profesor no se pudieron Registrar.",
                                        "Tipo"=>"error"
                                    ];
                                }
                            }else{
                                $alert=[
                                    "Alerta"=>"simple",
                                    "Titulo"=>"Ocurrio un error Inesperado",
                                    "Texto"=>"La cuenta no se pudo registrar, Verifique nuevamente.",
                                    "Tipo"=>"error"
                                ];
                            }
                        }
                    }
                     return mainModel::sweet_alerts($alert);
                
        }
    }   