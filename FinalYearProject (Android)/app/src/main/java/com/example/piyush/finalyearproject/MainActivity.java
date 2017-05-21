package com.example.piyush.finalyearproject;

import android.app.AlertDialog;
import android.app.DatePickerDialog;
import android.app.Dialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.location.LocationManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GoogleApiAvailability;

import java.util.Calendar;

public class MainActivity extends AppCompatActivity implements View.OnClickListener{

    private static final int PLAY_SERVICES_RESOLUTION_REQUEST = 9000;
    private static final String TAG = "MainActivity";

    RadioGroup radioGroup_Shop_Event;
    RadioButton radioShop,radioEvents;
    LinearLayout btn_Login_Register;
    Button btnLogin,btnRegister;

    GPSTracker gps;
    SessionManager session;

    private Calendar calendar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);


        radioGroup_Shop_Event = (RadioGroup)findViewById(R.id.radioGroup_shop_event);
        radioShop = (RadioButton)findViewById(R.id.radioShop);
        radioShop.setOnClickListener(this);
        radioEvents = (RadioButton)findViewById(R.id.radioEvents);
        btn_Login_Register = (LinearLayout)findViewById(R.id.btn_Login_Register);
        btnLogin = (Button)findViewById(R.id.btnLogin);
        btnLogin.setOnClickListener(this);
        btnRegister = (Button)findViewById(R.id.btnRegister);
        btnRegister.setOnClickListener(this);
        calendar = Calendar.getInstance();
        gps = new GPSTracker(this);
        session = new SessionManager(getApplicationContext());
        if(checkPermisson())
        {
            if(session.IsLoggedIn())
            {
                btn_Login_Register.setVisibility(View.GONE);
                LinearLayout.LayoutParams params = (LinearLayout.LayoutParams)radioGroup_Shop_Event.getLayoutParams();
                params.setMargins(0, 350, 0, 0);
                radioGroup_Shop_Event.setLayoutParams(params);
            }
        }
        else
        {
            Intent error_i= new Intent(getApplicationContext(),ErrorMessage.class);
            session.setErrorMessage("Something Went Wrong");
            startActivity(error_i);
        }
        if (checkPlayServices()) {
            Intent intent = new Intent(this, RegistrationIntentService.class);
            Log.d("starting reg service", String.valueOf(startService(intent)));
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        if(session.IsLoggedIn())
            getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        switch (id){
            case R.id.menuLogout:
                Log.d("Logging Out", "");
                session.logoutUser();
                break;
        }
        return super.onOptionsItemSelected(item);
    }
    private boolean checkPermisson(){
        LocationManager locationManager = (LocationManager) getSystemService(LOCATION_SERVICE);
        if (locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)){
            if(isNetworkAvailable()) {
                if(gps.canGetLocation()){
                    return true;
                }else{
                    gps.showSettingsAlert();
                }
            } else {
                showINTERNETDisabledAlertToUser();
            }
        } else {
            showGPSDisabledAlertToUser();
        }
        return false;
    }

    private void showINTERNETDisabledAlertToUser(){
        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
        alertDialogBuilder.setMessage("Your Internet Connection is not Active")
                .setCancelable(false)
                .setNegativeButton("Okay",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                session.setErrorMessage("Internet Is not working Nice");
                                startActivity(new Intent(MainActivity.this, ErrorMessage.class));
                            }
                        });
        AlertDialog alert = alertDialogBuilder.create();
        alert.show();
    }

    private void showGPSDisabledAlertToUser(){
        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
        alertDialogBuilder.setMessage("GPS is disabled in your Phone. Please activate iot before you proceed")
                .setCancelable(false)
                .setPositiveButton("Activate GPS",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                Intent callGPSSettingIntent = new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                                startActivity(callGPSSettingIntent);
                            }
                        }).setNegativeButton("Cancel",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        session.setErrorMessage("GPS Is not working Nice");
                        startActivity(new Intent(MainActivity.this, ErrorMessage.class));
                    }
                });
        AlertDialog alert = alertDialogBuilder.create();
        alert.show();
    }
    private boolean isNetworkAvailable() {
        ConnectivityManager connectivityManager
                = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();
    }

    public void setDate(View view) {showDialog(999);}
    @Override
    protected Dialog onCreateDialog(int id) {
        if (id == 999)
            return new DatePickerDialog(this, myDateListener,calendar.get(Calendar.YEAR),calendar.get(Calendar.MONTH),calendar.get(Calendar.DAY_OF_MONTH));
        return null;
    }

    private DatePickerDialog.OnDateSetListener myDateListener = new DatePickerDialog.OnDateSetListener() {
        @Override
        public void onDateSet(DatePicker arg0, int arg1, int arg2, int arg3) {
            Intent select_select_category_i = new Intent(getApplicationContext(),SelectCategory.class);
            session.setBIG_CAT("2");
            Log.d("Big Category Selection",session.getBIG_CAT());
            session.setDATE(String.valueOf(arg1),String.valueOf(arg2+1),String.valueOf(arg3));
            startActivity(select_select_category_i);
        }
    };
    @Override
    public void onClick(View v) {
        switch (v.getId()){
            case R.id.btnLogin :
                Log.d("redirecting","login");
                Intent Login_i = new Intent(getApplicationContext(),LogIn.class);
                startActivity(Login_i);
                break;
            case R.id.btnRegister :
                Intent Register_i = new Intent(getApplicationContext(), Register.class);
                startActivity(Register_i);
                break;
            case R.id.radioShop :
                session.setBIG_CAT("1");
                Log.d("Big Category Selection", session.getBIG_CAT());
                Intent Shop_i = new Intent(getApplicationContext(),SelectCategory.class);
                startActivity(Shop_i);
        }
    }
    @Override
    protected void onResume() {
        super.onResume();
    }
    @Override
    protected void onPause() {
        super.onPause();
    }
    private boolean checkPlayServices() {
        GoogleApiAvailability apiAvailability = GoogleApiAvailability.getInstance();
        int resultCode = apiAvailability.isGooglePlayServicesAvailable(this);
        if (resultCode != ConnectionResult.SUCCESS) {
            if (apiAvailability.isUserResolvableError(resultCode)) {
                apiAvailability.getErrorDialog(this, resultCode, PLAY_SERVICES_RESOLUTION_REQUEST)
                        .show();
            } else {
                Log.i(TAG, "This device is not supported.");
                finish();
            }
            return false;
        }
        return true;
    }
}