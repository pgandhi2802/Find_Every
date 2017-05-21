package com.example.piyush.finalyearproject;

import android.content.Intent;
import android.os.AsyncTask;
import android.support.v4.app.FragmentActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

public class ResultsOnMap extends FragmentActivity implements OnMapReadyCallback {

    SessionManager session;

    JSONParser jParser = new JSONParser();
    JSONArray result_array=null;

    private HashMap<Marker, Integer> mHashMap = new HashMap<Marker, Integer>();
    ArrayList<Shop_mini> result_shop = new ArrayList<Shop_mini>();

    private static final String TAG_SHOP_ID="shop_id";
    private static final String TAG_SHOP_NAME="shop_name";
    private static final String TAG_SHOP="shops";
    private static final String TAG_SHOP_LAT="shop_lat";
    private static final String TAG_SHOP_LNG="shop_lng";
    private static final String TAG_SUCCESS="success";

    String urls = null;
    int success=0;

    Double user_lat,user_lng;
    String user_city,user_big_cat,user_small_cat,user_id,user_day,user_month,user_year=null;

    private GoogleMap mMap;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_results_on_map);
        // Obtain the SupportMapFragment and get notified when the map is ready to be used.
        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);

        session = new SessionManager(getApplicationContext());
        Log.d("Big Category Selection",session.getBIG_CAT());
        if(session.IsLoggedIn())
        {
            user_id= String.valueOf(session.getUserId());
        }

        urls=getString(R.string.baseURL1)+"get_shop_location";

        new GetResult().execute();

    }


    @Override
    public void onMapReady(GoogleMap googleMap) {
        mMap = googleMap;
    }

    public void show_result() {
        Log.d("map_showcase", "acsd");
        for (Integer i=0;i< result_shop.size(); i++) {
            LatLng sydney = new LatLng(result_shop.get(i).getLng(),result_shop.get(i).getLat());
            Log.d("dssvsdfvsvdsvsvsd", String.valueOf(result_shop.get(i).getLng()+","+result_shop.get(i).getLat()));
            Marker marker = mMap.addMarker(new MarkerOptions().position(sydney).title(result_shop.get(i).getName()));
            mMap.moveCamera(CameraUpdateFactory.newLatLng(sydney));
            Log.d("checking", String.valueOf(result_shop.get(i).getLat()));
            mHashMap.put(marker, result_shop.get(i).getID());
            mMap.moveCamera(CameraUpdateFactory.newLatLng(sydney));
        }
        mMap.setOnMarkerClickListener(new GoogleMap.OnMarkerClickListener()
        {

            @Override
            public boolean onMarkerClick(Marker marker) {
                int pos = mHashMap.get(marker);
                Log.i("Position of arraylist", pos+"");
                return false;
            }

        });
        mMap.setOnInfoWindowClickListener(new GoogleMap.OnInfoWindowClickListener() {

            @Override
            public void onInfoWindowClick(Marker marker) {
                int pos = mHashMap.get(marker);
                Log.i("Position of arraylist", pos + "");
                session.setShop_id(pos);
                Intent redirect_to_detail = new Intent(getApplicationContext(), Show_details.class);
                startActivity(redirect_to_detail);
            }
        });
        mMap.setOnMarkerClickListener(new GoogleMap.OnMarkerClickListener() {

            @Override
            public boolean onMarkerClick(Marker marker) {
                int pos = mHashMap.get(marker);
                Log.i("Position of arraylist", pos + "");
                return false;
            }

        });
    }

    protected void set_marker_value(){
        try {
            for (int i = 0; i < result_array.length(); i++) {
                JSONObject jsonobjstatus = result_array.getJSONObject(i);
                Double lat = jsonobjstatus.getDouble(TAG_SHOP_LNG);
                Double lng = jsonobjstatus.getDouble(TAG_SHOP_LAT);
                String shop_name = jsonobjstatus.getString(TAG_SHOP_NAME);
                Integer shop_id = jsonobjstatus.getInt(TAG_SHOP_ID);
                result_shop.add(new Shop_mini(lat,lng,shop_id,shop_name));
            }
            show_result();
        }
        catch (Exception e){
            Log.i("Exception", String.valueOf(e));
        }
    }



    class GetResult extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            if(session.IsdateSelected())
            {
                nameValuePair.add(new BasicNameValuePair("user_day", session.getDay()));
                Log.d("day", session.getDay());
                nameValuePair.add(new BasicNameValuePair("user_month", session.getMonth()));
                Log.d("month", session.getMonth());
                nameValuePair.add(new BasicNameValuePair("user_year", session.getYear()));
                Log.d("year", session.getYear());
            }
            nameValuePair.add(new BasicNameValuePair("user_small_cat", session.getSmallCat()));
            Log.d("category_selection", session.getSmallCat());
            nameValuePair.add(new BasicNameValuePair("user_big_cat", session.getBIG_CAT()));
            Log.d("Big_category_selection", session.getBIG_CAT());
            if(session.IsCitySelected())
            {
                Log.d("city Selection",session.getCity());
                nameValuePair.add(new BasicNameValuePair("user_city_selected","1"));
                nameValuePair.add(new BasicNameValuePair("user_city", session.getCity()));
                nameValuePair.add(new BasicNameValuePair("user_radius",session.getRadius()));
            }
            else
            {
                nameValuePair.add(new BasicNameValuePair("user_city_selected", "0"));
                nameValuePair.add(new BasicNameValuePair("user_lat", session.getUserLat()));
                nameValuePair.add(new BasicNameValuePair("user_lng", session.getUserLng()));
            }
            if(session.IsLoggedIn())
                nameValuePair.add(new BasicNameValuePair("user_email", String.valueOf(session.getUserId())));

            try {
                JSONObject json = jParser.makeHttpRequest(urls, "POST", nameValuePair);

                success = json.getInt(TAG_SUCCESS);
                Log.d("success Status", String.valueOf(success));
//                Log.d("query",json.getString("query"));
                result_array=json.getJSONArray(TAG_SHOP);
                Log.d("csds", String.valueOf(result_array));
            }
            catch (Exception d){
                Log.d("dcsdcsv", String.valueOf(d));
            }

            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            if(success==1)
                set_marker_value();
            else
            {
                Intent redirect_i=new Intent(getApplicationContext(),MainActivity.class);
                startActivity(redirect_i);
            }
        }
    }

}


class Shop_mini{
    private Double lat;
    private Double lng;
    private Integer ID;
    private String name;
    public Shop_mini(Double lat1,Double lng1,Integer ID1,String name1){
        Log.d("sdfds", String.valueOf(lat1));
        lat=lat1;
        lng=lng1;
        ID=ID1;
        name=name1;
    }
    public Integer getID(){
        return ID;
    }
    public Double getLat(){
        return lat;
    }
    public Double getLng(){
        return lng;
    }
    public String getName(){
        return name;
    }
}