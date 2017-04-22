import { Component } from '@angular/core';

import { LandingModule } from 'app/landing/landing.module';
import { FeedModule } from 'app/feed/feed.module';
import { GroupModule } from 'app/group/group.module';
import { ProfileModule } from 'app/profile/profile.module';
import { SharedModule } from 'app/shared/shared.module';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'app works!';
}
