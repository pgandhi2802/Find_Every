package com.example.piyush.finalyearproject;

import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.RatingBar;
import android.widget.TextView;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class Show_details extends AppCompatActivity implements View.OnClickListener{

    SessionManager session;

    Integer shop_id,user_id,success_rating;

    TextView txtName,txtEmail,txtCity,txtPincode,txtContactNo,txtAddress,txtDescription,txtRating;
    RatingBar ratingBar,ratingAvgBar;
    TextView reviewUser1,reviewUser2,reviewUser3,reviewUser4,reviewUser5,review1,review2,review3,review4,review5;
    Button btnRating;


    EditText editReview;
    Button submitReview;


    JSONParser jParser = new JSONParser();
    JSONArray details_array=null;
    JSONArray review_array=null;


    String urls=null;
    Integer success=0;
    double rating=1.0;
    double avgRating=1.0;

    String givenRate=null,givenReview=null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_show_details);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        editReview = (EditText)findViewById(R.id.editReview);
        submitReview = (Button)findViewById(R.id.submitReview);


        btnRating = (Button)findViewById(R.id.btnRating);

        txtName = (TextView)findViewById(R.id.txtName);
        txtEmail = (TextView)findViewById(R.id.txtEmail);
        txtCity = (TextView)findViewById(R.id.txtCity);
        txtPincode = (TextView)findViewById(R.id.txtPincode);
        txtContactNo = (TextView)findViewById(R.id.txtContactNo);
        txtAddress = (TextView)findViewById(R.id.txtAddress);
        txtDescription = (TextView)findViewById(R.id.txtDescription);
        txtRating = (TextView)findViewById(R.id.txtRating);
        reviewUser1 = (TextView)findViewById(R.id.reviewUser1);
        reviewUser2 = (TextView)findViewById(R.id.reviewUser2);
        reviewUser3 = (TextView)findViewById(R.id.reviewUser3);
        reviewUser4 = (TextView)findViewById(R.id.reviewUser4);
        reviewUser5 = (TextView)findViewById(R.id.reviewUser5);
        review1 = (TextView)findViewById(R.id.review1);
        review2 = (TextView)findViewById(R.id.review2);
        review3 = (TextView)findViewById(R.id.review3);
        review4 = (TextView)findViewById(R.id.review4);
        review5 = (TextView)findViewById(R.id.review5);

        ratingBar = (RatingBar)findViewById(R.id.ratingBar);


        btnRating.setOnClickListener(this);
        submitReview.setOnClickListener(this);

        ratingAvgBar = (RatingBar)findViewById(R.id.ratingAvgBar);


        session = new SessionManager(getApplicationContext());

        shop_id=session.getShop_id();

        user_id=session.getUserId();

        if(!session.IsLoggedIn()) {
            ratingBar.setVisibility(View.GONE);
            btnRating.setVisibility(View.GONE);
            txtRating.setVisibility(View.GONE);
            editReview.setVisibility(View.GONE);
            submitReview.setVisibility(View.GONE);
        }

        urls=getString(R.string.baseURL1)+"get_shop_details";

        new GetDetails().execute();



    }

    @Override
    public void onClick(View v) {
        switch(v.getId()){
            case R.id.btnRating :
                givenRate = String.valueOf(ratingBar.getRating());
                new changeRating().execute();
                break;
            case R.id.submitReview :
                givenReview = editReview.getText().toString();
                new giveReview().execute();
        }
    }

    class changeRating extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            String ratign_urls = getString(R.string.baseURL1)+"rate_shop";
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            nameValuePair.add(new BasicNameValuePair("shop_id",String.valueOf(shop_id)));
            nameValuePair.add(new BasicNameValuePair("user_id", String.valueOf(user_id)));
            nameValuePair.add(new BasicNameValuePair("rating",givenRate));
            JSONObject json = jParser.makeHttpRequest(ratign_urls, "POST",nameValuePair);
            try {
                success_rating = json.getInt("success");
            }
            catch(Exception e){
                Log.i("Exception", String.valueOf(e));
            }
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            if(success_rating==1)
            {
                Intent redirect_rating_i= new Intent(getApplicationContext(),Show_details.class);
                startActivity(redirect_rating_i);
            }
        }
    }

    class giveReview extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            String ratign_urls = getString(R.string.baseURL1)+"review_shop";
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            nameValuePair.add(new BasicNameValuePair("shop_id",String.valueOf(shop_id)));
            nameValuePair.add(new BasicNameValuePair("user_id", String.valueOf(user_id)));
            nameValuePair.add(new BasicNameValuePair("review",givenReview));
            JSONObject json = jParser.makeHttpRequest(ratign_urls, "POST",nameValuePair);
            try {
                success_rating = json.getInt("success");
            }
            catch(Exception e){
                Log.i("Exception", String.valueOf(e));
            }
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            if(success_rating==1)
            {
                Intent redirect_rating_i= new Intent(getApplicationContext(),Show_details.class);
                startActivity(redirect_rating_i);
            }
        }
    }



    protected void Show_details(){
        try {
            for (int i = 0; i < details_array.length(); i++) {
                JSONObject jsonobjstatus = details_array.getJSONObject(i);
                txtName.setText(jsonobjstatus.getString("shop_name"));
                txtEmail.setText(jsonobjstatus.getString("shop_email"));
                txtCity.setText(jsonobjstatus.getString("shop_city"));
                txtPincode.setText(jsonobjstatus.getString("shop_pincode"));
                txtContactNo.setText(jsonobjstatus.getString("shop_contact_no"));
                txtAddress.setText(jsonobjstatus.getString("shop_add_line_1")+jsonobjstatus.getString("shop_add_line_2"));
                txtDescription.setText(jsonobjstatus.getString("shop_descr"));
            }

            ratingBar.setRating((float) rating);
            ratingAvgBar.setRating((float) avgRating);
            JSONObject jsonobjstatus = review_array.getJSONObject(0);
            if(jsonobjstatus.getString("review")!=null) {
                reviewUser1.setText("Review#1");
                review1.setText(jsonobjstatus.getString("review"));
            }
            else {
                reviewUser1.setVisibility(View.GONE);
                review1.setVisibility(View.GONE);
            }
            JSONObject jsonobjstatus0 = review_array.getJSONObject(1);
            if(jsonobjstatus.getString("review")!=null) {
                reviewUser2.setText("Review#2");
                review2.setText(jsonobjstatus0.getString("review"));
            }
            else {
                reviewUser2.setVisibility(View.GONE);
                review2.setVisibility(View.GONE);
            }
            JSONObject jsonobjstatus1 = review_array.getJSONObject(2);
            if(jsonobjstatus.getString("review")!=null) {
                reviewUser3.setText("Review#3");
                review3.setText(jsonobjstatus1.getString("review"));
            }
            else {
                reviewUser3.setVisibility(View.GONE);
                review3.setVisibility(View.GONE);
            }
            JSONObject jsonobjstatus2 = review_array.getJSONObject(3);
            if(jsonobjstatus.getString("review")!=null) {
                reviewUser4.setText("Review#4");
                review4.setText(jsonobjstatus2.getString("review"));
            }
            else {
                reviewUser4.setVisibility(View.GONE);
                review4.setVisibility(View.GONE);
            }
            JSONObject jsonobjstatus3 = review_array.getJSONObject(4);
            if(jsonobjstatus.getString("review")!=null) {
                reviewUser5.setText("Review#5");
                review5.setText(jsonobjstatus3.getString("review"));
            }
            else {
                reviewUser5.setVisibility(View.GONE);
                review5.setVisibility(View.GONE);
            }
        }
        catch (Exception e){
            Log.i("Exception", String.valueOf(e));
        }
    }
    class GetDetails extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            Log.d("shop_id", String.valueOf(shop_id));
            nameValuePair.add(new BasicNameValuePair("shop_id",String.valueOf(shop_id)));
            if(session.IsLoggedIn()) {
                Log.d("user_id", String.valueOf(user_id));
                nameValuePair.add(new BasicNameValuePair("user_id", String.valueOf(user_id)));
            }
            JSONObject json = jParser.makeHttpRequest(urls, "POST",nameValuePair);
            try {
                Log.d("s", "dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd");
                success = json.getInt("success");
                Log.d("success Status", String.valueOf(success));
                details_array=json.getJSONArray("shop_details");
                Log.d("shop_details","sdvxdfvfvfd");
                review_array=json.getJSONArray("shop_review");
                Log.d("fssdv", String.valueOf(review_array));
                rating=json.getDouble("shop_user_rating");
                Log.d("rating", String.valueOf(rating));
                avgRating=json.getDouble("shop_avg_rating");
                Log.d("avgRating", String.valueOf(avgRating));
            }
            catch(Exception e){
                Log.i("Exception", String.valueOf(e));
            }
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            if(success==1)
                Show_details();
            else
            {
                Intent redirect_i=new Intent(getApplicationContext(),MainActivity.class);
                startActivity(redirect_i);
            }
        }
    }
}
