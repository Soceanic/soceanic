import { Component, OnInit } from '@angular/core';
import { Post } from '../api/feed/post';
import { FeedService } from '../api/feed/feed.service';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';

@Component({
  selector: 'app-feed',
  templateUrl: './feed.component.html',
  styleUrls: ['./feed.component.css']
})
export class FeedComponent implements OnInit {

  feed: [Post];
  errorMessage: any;
  currentUser: string = 'borisirl';


  constructor(private service: FeedService) { }

  ngOnInit() {
    this.service.get()
                 .subscribe(
                   posts => this.feed = posts,
                   error => this.errorMessage = <any> error
                 );
  }


}
