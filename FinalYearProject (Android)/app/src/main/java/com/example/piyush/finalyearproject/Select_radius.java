package com.example.piyush.finalyearproject;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.RadioButton;

public class Select_radius extends AppCompatActivity implements View.OnClickListener{

    RadioButton radioButton1,radioButton2,radioButton3,radioButton4,radioButton5,radioButton6;

    SessionManager session;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_radius);

        radioButton1 = (RadioButton)findViewById(R.id.radioButton1);
        radioButton2 = (RadioButton)findViewById(R.id.radioButton2);
        radioButton3 = (RadioButton)findViewById(R.id.radioButton3);
        radioButton4 = (RadioButton)findViewById(R.id.radioButton4);
        radioButton5 = (RadioButton)findViewById(R.id.radioButton5);
        radioButton6 = (RadioButton)findViewById(R.id.radioButton6);

        session = new SessionManager(getApplicationContext());

        radioButton1.setOnClickListener(this);
        radioButton2.setOnClickListener(this);
        radioButton3.setOnClickListener(this);
        radioButton4.setOnClickListener(this);
        radioButton5.setOnClickListener(this);
        radioButton6.setOnClickListener(this);

    }

    @Override
    public void onClick(View v) {
        switch (v.getId()){
            case R.id.radioButton1 :
                    session.setRadius("5");
                break;
            case R.id.radioButton2 :
                    session.setRadius("10");
                break;
            case R.id.radioButton3 :
                    session.setRadius("15");
                break;
            case R.id.radioButton4 :
                    session.setRadius("20");
                break;
            case R.id.radioButton5 :
                    session.setRadius("25");
                break;
            case R.id.radioButton6 :
                    session.setRadius("30");
                break;
        }

        Intent i = new Intent(getApplicationContext(),ResultsOnMap.class);
        startActivity(i);
    }
}
