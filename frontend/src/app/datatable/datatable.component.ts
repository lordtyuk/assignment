import {Component, ViewChild} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {catchError, map, shareReplay, tap} from "rxjs";
import {MatSort} from "@angular/material/sort";
import {MatPaginator} from "@angular/material/paginator";
import {environment} from "../../environments/environment";
import {FormBuilder, FormControl, FormGroup} from "@angular/forms";

interface Model {
  name: string;
  location: {
    name: string;
    code: string;
  }
  storageSizeGB: number;
  storageType: {
    name: string;
  }
  ramCount: number;
  ramType: {
    name: string;
  }
}

@Component({
  selector: 'app-datatable',
  templateUrl: './datatable.component.html',
  styleUrls: ['./datatable.component.scss']
})
export class DatatableComponent {

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;

  models: Model[] = [];
  serverModels$ = this.http.get<Model[]>(environment.apiUrl + '/model').pipe(
    catchError(() => []),
    map(models => models ?? []),
    tap(models => this.models = models),
    shareReplay()
  );

  nameControl: FormControl;
  locationControl: FormControl;
  ramControl: FormControl;
  storageControl: FormControl;

  group: FormGroup;

  constructor(
    private http: HttpClient,
    private fb: FormBuilder
  ) {
    this.group = fb.group({});
    this.nameControl = fb.control('');
    this.locationControl = fb.control('');

    this.group.addControl('name', this.nameControl);
    this.group.addControl('location', this.locationControl);
  }

  getStorageSize(storageSizeGB: any) {
    const size = parseInt(storageSizeGB);
    if (size > 1024) {
      return Math.round(size / 1024) + 'TB';
    }
    return size + 'GB';
  }
}
