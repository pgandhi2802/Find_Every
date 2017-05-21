package com.example.piyush.finalyearproject;

import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class LogIn extends AppCompatActivity implements View.OnClickListener{

    SessionManager session;

    EditText edittxtUserName,edittxtPassword;
    TextView txtError;
    Button btnLogin;

    JSONParser jParser = new JSONParser();
    JSONArray user_login_array=null;

    private static final String TAG_USER_ID="user_id";
    private static final String TAG_USER_NAME="user_name";
    private static final String TAG_USER="user";
    private static final String TAG_SUCCESS="success";


    String user_password,user_name;
    String urls=null;
    int success=0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_log_in);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        urls=getString(R.string.baseURL1)+"login";

        edittxtUserName = (EditText)findViewById(R.id.edittxtUserName);
        edittxtPassword = (EditText)findViewById(R.id.edittextPassword);
        txtError = (TextView)findViewById(R.id.txtError);
        btnLogin = (Button)findViewById(R.id.btnLogin);

        btnLogin.setOnClickListener(this);

        session  = new SessionManager(getApplicationContext());

        if(session.IsLoggedIn())
        {
            Intent i = new Intent(getApplicationContext(),MainActivity.class);
            startActivity(i);
        }

    }



    @Override
    public void onClick(View v) {
        if(v.getId()==R.id.btnLogin){
            if(edittxtUserName.getText().toString().matches("") || edittxtPassword.getText().toString().matches("")){
                txtError.setText("Please provide all details");
            }
            else
            {
                Log.d("checking","login");
                user_password = edittxtPassword.getText().toString();
                user_name = edittxtUserName.getText().toString();
                new CheckLogIn().execute();
            }
        }
    }

    private void set_login(){
        Log.d("log in STATUS", "true");
        JSONObject jsonobjstatus = null;
        try {
            jsonobjstatus = user_login_array.getJSONObject(0);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        try {
            String user_ID=jsonobjstatus.getString(TAG_USER_ID);
            String user_name=jsonobjstatus.getString("email");
            if(session.setLoggedIn(Integer.valueOf(user_ID),user_name))
            {
                Intent redirect_to_mainActivity= new Intent(getApplicationContext(),MainActivity.class);
                startActivity(redirect_to_mainActivity);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    class CheckLogIn extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            nameValuePair.add(new BasicNameValuePair(TAG_USER_NAME,user_name));
            nameValuePair.add(new BasicNameValuePair("password",user_password));
            JSONObject json = jParser.makeHttpRequest(urls, "POST",nameValuePair);
            try {
                success = json.getInt(TAG_SUCCESS);
                if(success==1)
                {
                    user_login_array=json.getJSONArray(TAG_USER);
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            if(success==1)
                set_login();
            else
            {
                txtError.setText("Something went wrong! Please Try Again");
            }
        }
    }


}
