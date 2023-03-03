<?php

class User extends Database
{
    public static $userID = null;
    public static $getUser = false;


    public static function login($mail, $password, $remember=0)
    {
        $result = self::table("users")
                        ->whereRaw("userNickName=? OR userEmail=?", [$mail, $mail])
                        ->where(["password"=>md5($password), "status"=>1])
                        ->first();

        if($result)
        {
            self::$getUser = $result;
            self::$userID = $result->ID;
            $_SESSION["mu_user"] = $result;
            
            if($remember==1)
            {
                setcookie("mu_user_cookie", $result, time(+(24*60*60)));
            }
            
            return true;
        }
        else{
            return false;
        }
    }


    public static function forgotPassword($mail, $siteUrl)
    {
        $result = self::table("users")->where(["userEmail"=>$mail, "status"=>1])->first();

        if($result)
        {
            // We create a token:
            $token = uniqid();
            
            $passwordTokenAdd = self::table("password_tokens")->updateOrCreate(["userID"=>$result->ID], ["userID"=>$result->ID, "token"=>$token]);
            
            if($passwordTokenAdd)
            {
                $message = "<p> Monsieur <strong>".$result->userNameSurname."</strong> </p>, 
                            <p> Veuillez cliquez sur le lien pour réinitialiser votre mot de passe: </p>
                            <p> <a href='".$siteUrl."forgot-password/".$token."' style='background:#607d8b; color:#fff; width:120px; padding:8px; font-size:16px'>Réinitialiser votre mot de passe</a> </p>
                            <p> Ne partagez pas ce lien pour votre sécurité </p>";
                            
                return self::mail($result->userEmail, $result->userNameSurname, "Réinitialisation de mot de passe", $message);
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }


    public static function controlToken($token)
    {
        self::table('password_tokens')->where('token', $token)->first();
    }


    public static function resetPassword($mail, $password, $confirm_password)
    {
        if($password!=$confirm_password)
            return false;

        $result = self::table("users")->where(["userEmail"=>$mail, "status"=>1])->first();

        if($result)
        {
            $passwordUpdate = self::table("users")->where("ID", $result->ID)->update(["userPassword"=>md5($password)]);
            
            if($passwordUpdate)
            {
                $tokenDelete = self::table("password_tokens")->where("userID", $result->ID)->delete();
                
                return self::login($result->userEmail, $confirm_password);
            }
            else{
                return false;
            }
           
        }
        else{
            return false;
        }
    }


}



?>