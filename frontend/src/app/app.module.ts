import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { LandingModule } from 'app/landing/landing.module';
import { FeedModule } from 'app/feed/feed.module';
import { GroupModule } from 'app/group/group.module';
import { ProfileModule } from 'app/profile/profile.module';
import { SharedModule } from 'app/shared/shared.module';

import { AppRoutingModule } from './app-routing.module';

import { AppComponent } from './app.component';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    SharedModule,
    LandingModule,
    FeedModule,
    GroupModule,
    ProfileModule,
    AppRoutingModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
