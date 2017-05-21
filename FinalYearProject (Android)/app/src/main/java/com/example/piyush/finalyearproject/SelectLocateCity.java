package com.example.piyush.finalyearproject;

import android.annotation.TargetApi;
import android.content.Intent;
import android.content.res.ColorStateList;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.google.android.gms.maps.GoogleMap;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static com.example.piyush.finalyearproject.R.color.txtColor;

public class SelectLocateCity extends AppCompatActivity implements View.OnClickListener{

    private GoogleMap mMap;
    GPSTracker gps;

    double latitude,longitude;


    Button btnLocateMe;
    RadioGroup radioCity;

    SessionManager session;

    JSONParser jParser = new JSONParser();
    JSONArray city_array=null;

    private static final String TAG_CITY_ID="city_id";
    private static final String TAG_CITY_NAME="city_name";
    private static final String TAG_CITY="city";
    private static final String TAG_SUCCESS="success";

    String urls = null;
    int success=0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_locate_city);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        gps = new GPSTracker(this);
        if(gps.canGetLocation()){
            if(gps.getLatitude()==0.0 || gps.getLongitude()==0.0)
            {
                latitude = -34.2423;
                longitude = 151.3243;
            }
            else {
                latitude = gps.getLatitude();
                longitude = gps.getLongitude();
            }

        }else {
            latitude = -34.2423;
            longitude = 151.3243;
        }

        Toast.makeText(getApplicationContext(), "Your Location is - \nLat: " + latitude + "\nLong: " + longitude, Toast.LENGTH_LONG).show();

        session = new SessionManager(getApplicationContext());
        urls=getString(R.string.baseURL1)+"get_city";

        Log.d("Big Category Selection",session.getBIG_CAT());

        btnLocateMe = (Button)findViewById(R.id.btnLocateMe);
        radioCity = (RadioGroup)findViewById(R.id.radioCity);

        btnLocateMe.setOnClickListener(this);

        new GetCity().execute();



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

    @Override
    public void onClick(View v) {
        switch(v.getId()){
            case R.id.btnLocateMe :
                Intent redirect_to_locate_map = new Intent(getApplicationContext(),Select_radius.class);
                session.setCitySelect(false);
                session.setGPSLocation(latitude, longitude);
                startActivity(redirect_to_locate_map);
                break;
        }

    }

    @TargetApi(Build.VERSION_CODES.LOLLIPOP)
    protected void show_city(){
        try {
            RadioGroup ll = new RadioGroup(this);
            ll.setOrientation(LinearLayout.VERTICAL);
            for (int i = 0; i < city_array.length(); i++) {
                JSONObject jsonobjstatus = city_array.getJSONObject(i);
                String str_uname = jsonobjstatus.getString(TAG_CITY_ID);
                String str_cat_name = jsonobjstatus.getString(TAG_CITY_NAME);
                RadioButton rdbtn = new RadioButton(this);
                rdbtn.setId(Integer.parseInt(str_uname));
                rdbtn.setText(str_cat_name);
                rdbtn.setTextColor(getResources().getColor(txtColor));
                rdbtn.setButtonTintList(ColorStateList.valueOf(getResources().getColor(txtColor)));
                ll.addView(rdbtn);
            }
            ((ViewGroup) findViewById(R.id.radioCity)).addView(ll);
            ll.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
                @Override
                public void onCheckedChanged(RadioGroup group, int checkedId) {
                    Log.d("some thing", String.valueOf(checkedId));
                    session.setCitySelect(true);
                    session.setCity(String.valueOf(checkedId));
                    Intent intent = new Intent(getApplicationContext(),ResultsOnMap.class);
                    startActivity(intent);
                }
            });
        }
        catch (Exception e){
            Log.i("Exception", String.valueOf(e));
        }
    }


    class GetCity extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.i("getCity", "entered");
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            nameValuePair.add(new BasicNameValuePair("parent_cat", session.getBIG_CAT()));
            JSONObject json = jParser.makeHttpRequest(urls, "POST",nameValuePair);
            try {
                success = json.getInt(TAG_SUCCESS);
                String sc=json.getString(TAG_SUCCESS);
                city_array=json.getJSONArray(TAG_CITY);
            }
            catch(Exception e){
                Log.i("Exception", String.valueOf(e));
            }
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            if(success==1)
                show_city();
            else
            {
                Intent redirect_i=new Intent(getApplicationContext(),MainActivity.class);
                startActivity(redirect_i);
            }
        }
    }
}
