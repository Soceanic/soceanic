import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';

import { AppComponent } from './app.component';
import { LandingPageComponent } from './landing-page/landing-page.component';
import { RegistrationComponent } from './registration/registration.component';
import { FeedComponent } from './feed/feed.component';
import { PostComponent } from './post/post.component';

import { FeedService } from './api/feed/feed.service';
import { PostModalComponent } from './post-modal/post-modal.component';


const routes: Routes = [
  { path: '', component: LandingPageComponent },
  { path: 'feed', component: FeedComponent}
]

@NgModule({
  declarations: [
    AppComponent,
    LandingPageComponent,
    RegistrationComponent,
    PostComponent,
    FeedComponent,
    PostModalComponent
  ],
  imports: [
    BrowserModule,
    ReactiveFormsModule,
    FormsModule,
    HttpModule,
    RouterModule.forRoot(routes)
  ],
  providers: [FeedService],
  bootstrap: [AppComponent]
})
export class AppModule { }
