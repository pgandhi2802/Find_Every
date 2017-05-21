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
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class Register extends AppCompatActivity implements View.OnClickListener{

    EditText edittxtFirstName,edittxtLastName,edittxtEmail,edittxtPhone,edittxtPassword,edittxtConfirmPasssword;
    Button btnRegister;
    TextView txtError;

    SessionManager session;

    JSONParser jParser = new JSONParser();
    private static final String TAG_SUCCESS="success";
    private static final String TAG_SERVER_ERROR="error";

    String txtFirstName,txtLastName,txtEmail,txtPhone,txtPassword,user_gcm_reg_id;

    String urls=null;
    int success=0;
    String strError=null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        urls=getString(R.string.baseURL1)+"register";
        Log.d("ssdc",urls);

        session = new SessionManager(getApplicationContext());
        user_gcm_reg_id=session.getGcmRegistration();
        Log.d("Registration Id",user_gcm_reg_id);

        edittxtFirstName = (EditText)findViewById(R.id.edittxtFirstName);
        edittxtLastName = (EditText)findViewById(R.id.edittxtLastName);
        edittxtEmail = (EditText)findViewById(R.id.edittxtEmail);
        edittxtPhone = (EditText)findViewById(R.id.edittxtPhone);
        edittxtPassword = (EditText)findViewById(R.id.edittxtPassword);
        edittxtConfirmPasssword = (EditText)findViewById(R.id.edittxtConfirmPassword);

        txtError=(TextView)findViewById(R.id.edittxtError);

        btnRegister = (Button)findViewById(R.id.btnRegister);
        btnRegister.setOnClickListener(this);

    }

    @Override
    public void onClick(View v) {
        switch (v.getId()){
            case R.id.edittxtError:
                break;
            case R.id.btnRegister:
                Log.d("registering","shgdvcgs");
                if(edittxtFirstName.getText().toString().matches("") ||
                        edittxtLastName.getText().toString().matches("") ||
                        edittxtEmail.getText().toString().matches("") ||
                        edittxtPhone.getText().toString().matches("")||
                        edittxtPassword.getText().toString().matches("")||
                        edittxtConfirmPasssword.getText().toString().matches("")) {
                    txtError.setText("Please fill all details");
                }
                else
                {
                    if(edittxtPassword.getText().toString().matches(edittxtConfirmPasssword.getText().toString()))
                    {
                        txtFirstName=edittxtFirstName.getText().toString();
                        txtLastName=edittxtLastName.getText().toString();
                        txtEmail=edittxtEmail.getText().toString();
                        txtPhone=edittxtPhone.getText().toString();
                        txtPassword=edittxtPassword.getText().toString();

                        new UserRegister().execute();
                    }
                    else
                        txtError.setText("Password Do Not Match");
                }
                break;
        }
    }

    public void setError(){
        txtError.setText(strError);
    }


    class UserRegister extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            nameValuePair.add(new BasicNameValuePair("userFirstName",txtFirstName));
            nameValuePair.add(new BasicNameValuePair("userLastName",txtLastName));
            nameValuePair.add(new BasicNameValuePair("userEmail",txtEmail));
            nameValuePair.add(new BasicNameValuePair("userPhone",txtPhone));
            nameValuePair.add(new BasicNameValuePair("userPassword",txtPassword));
            nameValuePair.add(new BasicNameValuePair("userRegID",user_gcm_reg_id));
            JSONObject json = jParser.makeHttpRequest(urls, "POST",nameValuePair);
            try {
                success = json.getInt(TAG_SUCCESS);
                Log.d("success Status", String.valueOf(success));
                if(success==0)
                {
                    strError=json.getString(TAG_SERVER_ERROR);
                    Log.d("Register",strError);
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            if(success==0)
                setError();
            else
            {
                Intent redirect_to_login = new Intent(getApplicationContext(),LogIn.class);
                startActivity(redirect_to_login);
            }
        }
    }

}
