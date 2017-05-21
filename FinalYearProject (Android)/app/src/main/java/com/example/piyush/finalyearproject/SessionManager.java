package com.example.piyush.finalyearproject;


import java.util.HashMap;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;

public class SessionManager{
    SharedPreferences pref;
    Editor editor;
    Context _context;
    int PRIVATE_MODE = 0;
    private static final String PREF_NAME = "FinalYearProject";
    private static final String ERROR_MESSAGE="error_message";
    private static final String LOGIN_STATUS="IsloggedIn";
    private static final String USER_ID="user_id";
    private static final String USER_EMAIL="user_email";
    private static final String BIG_CAT="big_cat";
    private static final String SMALL_CAT="small_cat";
    private static final String DATE_SELECTED="NotSelected";
    private static final String YEAR="year";
    private static final String MONTH="month";
    private static final String DAY="day";
    private static final String GPS_LATTITUDE="latitude_location";
    private static final String GPS_LONGITUDE="latitude_longitude";
    private static final String GCM_REGISTRATION="gcm_registration";
    public static final String GCM_TOKEN = "GCM_token";

    private static final String SHOP_ID="shop_id";

    private static final String CITY_SELECTED="IsCitySelected";
    private static final String CITY="city";
    public SessionManager(Context context){
        this._context = context;
        pref = _context.getSharedPreferences(PREF_NAME, PRIVATE_MODE);
        editor = pref.edit();
    }

    private static final String RADIUS = "radius";

    public void setErrorMessage(String errorMessage) {
        editor.putString(ERROR_MESSAGE,errorMessage);
        editor.commit();
    }

    public String getErrorMessage(){
        return pref.getString(ERROR_MESSAGE,"Error Message");
    }

    public boolean setLoggedIn(Integer user_ID,String user_name){
        editor.putInt(USER_ID,user_ID);
        editor.putString(USER_EMAIL, user_name);
        editor.putBoolean(LOGIN_STATUS, true);
        editor.commit();
        return true;
    }

    public Integer getUserId(){
        return pref.getInt(USER_ID, 0);
    }

    public void logoutUser(){
        editor.clear();
        editor.commit();
        Intent i = new Intent(_context, MainActivity.class);
        i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        _context.startActivity(i);
    }

    public boolean IsLoggedIn(){
        return pref.getBoolean(LOGIN_STATUS,false);
    }

    public void setBIG_CAT(String big_cat){
        editor.putString(BIG_CAT,big_cat);
        editor.commit();
    }

    public String getBIG_CAT(){
        return pref.getString(BIG_CAT,null);
    }

    public void setSMALL_CAT(String str){
        editor.putString(SMALL_CAT,str);
        editor.commit();
    }

    public String getSmallCat(){
        return pref.getString(SMALL_CAT,null);
    }

    public void setCitySelect(Boolean str){
        editor.putBoolean(CITY_SELECTED, str);
        editor.commit();
    }
    public boolean IsCitySelected(){
        return pref.getBoolean(CITY_SELECTED,false);
    }

    public void setCity(String str){
        editor.putString(CITY, str);
        editor.commit();
    }

    public String getCity(){
        return pref.getString(CITY,null);
    }

    public void setDATE(String year,String month,String day){
        editor.putBoolean(DATE_SELECTED,true);
        editor.putString(YEAR, year);
        editor.putString(MONTH, month);
        editor.putString(DAY, day);
        editor.commit();
    }

    public void setGPSLocation(Double latitude,Double longitude){
        editor.putString(GPS_LATTITUDE, String.valueOf(latitude));
        editor.putString(GPS_LONGITUDE, String.valueOf(longitude));
        editor.commit();
    }

    public String getUserLat(){
        return pref.getString(GPS_LATTITUDE,null);
    }

    public String getUserLng(){
        return pref.getString(GPS_LONGITUDE,null);
    }


    public Boolean IsdateSelected(){
        return pref.getBoolean(DATE_SELECTED, false);
    }

    public String getDay(){
        return pref.getString(DAY, null);
    }

    public String getMonth(){
        return pref.getString(MONTH,null);
    }

    public String getYear(){
        return pref.getString(YEAR,null);
    }

    public void setShop_id(Integer pos){
        editor.putInt(SHOP_ID, Integer.parseInt(String.valueOf(pos)));
        editor.commit();
    }
    public Integer getShop_id(){
        return pref.getInt(SHOP_ID, 0);
    }

    public void setGcmRegistration(String token){
        editor.putString(GCM_TOKEN,token);
        editor.commit();
    }

    public String getGcmRegistration(){
        return pref.getString(GCM_TOKEN,null);
    }

    public void setRadius(String str){
        editor.putString(RADIUS,str);
        editor.commit();
    }

    public String getRadius(){
        return pref.getString(RADIUS,null);
    }
}