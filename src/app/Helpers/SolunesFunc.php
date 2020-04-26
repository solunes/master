<?php 

namespace Solunes\Master\App\Helpers;

use Image;
use Storage;

class SolunesFunc {


    public static function get_action_field_labels($response, $action_field, $langs) {
        $response = '';
        if($action_field=='login-as'){
            $response .= '<td class="restore">Login</td>';
        } else if($action_field=='create-customer-note'){
            $response .= '<td class="restore">Nota</td>';
        } else if($action_field=='create-customer-contact'){
            $response .= '<td class="restore">Crear Cita</td>';
        } else if($action_field=='edit-customer-contact'){
            $response .= '<td class="restore">Contactar</td>';
        }
        return $response;
    }

    public static function get_action_field_values($response, $module, $model, $item, $action_field, $langs) {
        $response = '';
        if($action_field=='login-as'){
            $confirm_message = "'¿Está seguro que desea ingresar como este cliente? Se cerrará su sesión actual.'";
            $response .= '<td class="ineditable restore"><a onclick="return confirm('.$confirm_message.')" href="'.url('admin/login-as/'.$item->id).'">Login</a></td>';
        } else if($action_field=='create-customer-note'){
            $response .= '<td class="restore"><a class="lightbox" data-featherlight="ajax" href="'.url('admin/child-model/customer-note/create?parent_id='.$item->id).'&lightbox[width]=600&lightbox[height]=400">Nota</a></td>';
        } else if($action_field=='create-customer-contact'){
            $response .= '<td class="restore"><a class="lightbox" data-featherlight="ajax" href="'.url('admin/child-model/customer-contact/create?parent_id='.$item->id).'&lightbox[width]=600&lightbox[height]=400">Crear Cita</a></td>';
        } else if($action_field=='edit-customer-contact'){
            if($item->status=='pending'){
                $response .= '<td class="restore"><a class="lightbox" data-featherlight="ajax" href="'.url('admin/child-model/customer-contact/edit/'.$item->id).'?lightbox[width]=600&lightbox[height]=400">Contactado</a></td>';
            } else {
                $response .= '<td class="restore">-</td>';
            }
        }
        return $response;
    }

}